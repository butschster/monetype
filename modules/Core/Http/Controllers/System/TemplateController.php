<?php

namespace Modules\Core\Http\Controllers\System;

use Auth;
use Lang;
use View;
use Meta;
use Assets;

class TemplateController extends Controller
{

    /**
     * @var  boolean  auto render template
     **/
    public $autoRender = true;

    /**
     * @var array
     */
    public $templateScripts = [];

    /**
     * @var View|string|null
     */
    public $layout = null;


    public function before()
    {
        parent::before();

        if ($this->autoRender === true) {
            $this->registerMedia();
        }
    }


    public function after()
    {
        parent::after();

        if ($this->autoRender === true) {
            Assets::group('global', 'layoutScripts', '<script type="text/javascript">' . $this->getTemplateScriptsAsString() . '</script>', 'global');

            view()
                ->share([
                    'currentUser' => $this->user,
                    'bodyId' => $this->getRouterPath()
                ]);
        }
    }


    /**
     * Execute an action on the controller.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        if ($this->autoRender === true) {
            $this->initLayout();
        }

        $response = parent::callAction($method, $parameters);

        if (is_null($response) && $this->autoRender === true && ! is_null($this->layout)) {
            $response = $this->response->setContent($this->layout);
        }

        return $response;
    }


    protected function registerMedia()
    {
        $this->templateScripts = [
            'SITE_URL'        => url(),
            'LOCALE'          => Lang::getLocale(),
            'USER_ID'         => Auth::id(),
            'URL'             => $this->request->url(),
            'MESSAGE_ERRORS'  => view()->shared('errors')->getBag('default'),
            'MESSAGE_SUCCESS' => (array) $this->session->get('success', []),
        ];
    }


    /**
     * @return string
     */
    protected function getTemplateScriptsAsString()
    {
        $script = '';
        foreach ($this->templateScripts as $var => $value) {
            if ($value instanceof Jsonable) {
                $value = $value->toJson();
            } else {
                $value = json_encode($value);
            }

            $script .= "var {$var} = {$value};\n";
        }

        return $script;
    }


    /**
     * @param string $key
     * @param string $file
     */
    protected function includeMergedMediaFile($key, $file)
    {
        $mediaContent = '<script type="text/javascript">' . Assets::mergeFiles($file, 'js') . "</script>";
        Assets::group('global', $key, $mediaContent, 'global');
    }


    /**
     * @param $filename
     */
    protected function includeModuleMediaFile($filename)
    {
        if (ModulesFileSystem::findFile('resources/js', $filename, 'js')) {
            Assets::js('include.' . $filename, '/module/js/' . $filename . '.js', 'core', false);
        }
    }


    /**
     * Setup the layout used by the controller.
     *
     * @return $this
     */
    protected function initLayout()
    {
        if ( ! is_null($this->layout) and ! ( $this->layout instanceof View )) {
            $this->layout = view($this->layout);
        }

        return $this;
    }


    /**
     * @param       $view
     * @param array $data
     *
     * @return View
     */
    protected function setLayout($view, array $data = [])
    {
        if ( ! is_null($this->layout)) {
            $content = view($this->wrapNamespace($view), $data);
            $this->layout->with('content', $content);

            return $content;
        }

        return view($this->wrapNamespace($view), $data);
    }


    /**
     * @param $title
     *
     * @return $this
     */
    protected function setTitle($title)
    {
        view()->share('pageTitle', $title);
        Meta::setTitle($title);

        return $this;
    }


    /**
     * @param string $separator
     *
     * @return string
     */
    protected function getRouterPath($separator = '.')
    {
        if ( ! is_null($this->getRouter())) {
            $controller = $this->getRouter()->currentRouteAction();
            $namespace  = array_get($this->getRouter()->getCurrentRoute()->getAction(), 'namespace');
            $path       = trim(str_replace($namespace, '', $controller), '\\');

            return str_replace(['\\', '@', '..', '.controller.'], $separator, snake_case($path, '.'));
        }

        return null;
    }


    /**
     * @param string| array $message
     * @param string|null   $url
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function successRedirect($message, $url = null)
    {
        return $this->redirect($url)->with('success', $message);
    }


    /**
     * @param string| array $message
     * @param string|null   $url
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function errorRedirect($message, $url = null)
    {
        return $this->redirect($url)->withErrors($message);
    }


    /**
     * @param string|null $url
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirect($url = null)
    {
        if (is_null($url)) {
            $redirect = back();
        } else {
            $redirect = redirect($url);
        }

        return $redirect;
    }
}