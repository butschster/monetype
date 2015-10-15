<?php

namespace Modules\Core\Http\Controllers\System;

use ModulesFileSystem;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Modules\Users\Model\User;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Core\Exceptions\ValidationException;
use Modules\Core\Exceptions\ControllerException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \Session
     */
    protected $session;

    /**
     * @var User;
     */
    protected $user;

    /**
     * @var string|null
     */
    public $moduleNamespace = null;


    public function __construct()
    {
        app()->call([$this, 'initController']);

        // Execute method boot() on controller execute
        if (method_exists($this, 'boot')) {
            app()->call([$this, 'boot']);
        }
    }


    /**
     * @param Request      $request
     * @param Response     $response
     * @param SessionStore $session
     */
    public function initController(Request $request, Response $response, SessionStore $session, Guard $auth)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->session  = $session;

        $this->loadCurrentUser($auth);
    }


    /**
     * Execute before an action executed
     * return void
     */
    public function before()
    {
    }


    /**
     * Execute after an action executed
     * return void
     */
    public function after()
    {
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
        try {
            $this->before();
            $response = call_user_func_array([$this, $method], $parameters);
            $this->after($response);

            return $response;

        } catch (ModelNotFoundException $e) {
            $model = $e->getModel();
            if (method_exists($model, 'getNotFoundMessage')) {
                $message = app()->make($model)->getNotFoundMessage();
            } else {
                $message = trans('core::core.message.model_not_found');
            }

            abort(404, $message);
        } catch (ControllerException $e) {
            $e->throwFailException();
        } catch (ValidationException $e) {
            $this->throwValidationException($this->request, $e->getValidator());
        }
    }


    /**
     * @param string       $ability
     * @param string|array $arguments
     * @param string|null  $message
     */
    public function checkPermissions($ability, $arguments = [], $message = null)
    {
        if (is_null($message)) {
            $message = trans('core::core.message.gate_not_allowed');
        }

        if ($this->user->cannot($ability, $arguments)) {
            abort(403, $message);
        }
    }


    /**
     * @param RedirectResponse $response
     *
     * @throws HttpResponseException
     */
    public function throwFailException(RedirectResponse $response)
    {
        throw new HttpResponseException($response);
    }


    /**
     * @param string $message
     *
     * @throws ControllerException
     */
    public function throwControllerException($message)
    {
        throw new ControllerException($message);
    }


    /**
     * @return string
     */
    protected function getModuleNamespace()
    {
        if (is_null($this->moduleNamespace)) {
            return ModulesFileSystem::getModuleNameByNamespace(get_class($this)) . '::';
        }

        return $this->moduleNamespace;
    }


    /**
     * @param string $string
     *
     * @return string
     */
    protected function wrapNamespace($string)
    {
        if (strpos($string, '::') === false) {
            $string = $this->getModuleNamespace() . $string;
        }

        return $string;
    }


    /**
     * @param Guard $auth
     */
    protected function loadCurrentUser(Guard $auth)
    {
        $this->user = $auth->user();
    }
}
