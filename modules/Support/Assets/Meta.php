<?php

namespace Modules\Support\Assets;

use HTML;
use Assets;
use Modules\Support\Contracts\SocialMediaTaggable;

class Meta
{

    /**
     * @var string
     */
    public $defaultGroup = 'frontend';


    public function __construct()
    {
        $this->setTitle();
    }


    /**
     * @param string|null $title
     *
     * @return mixed
     */
    public function setTitle($title = null)
    {
        if ( ! empty($title)) {
            $title .= ' / ' . trans('core::core.title.app');
        } else {
            $title = trans('core::core.title.app');
        }

        return $this->addToGroup('title', '<title>:title</title>', [
            ':title' => e(strip_tags($title))
        ]);
    }


    /**
     * @param string $description
     *
     * @return Meta
     */
    public function setDescription($description)
    {
        return $this->addMeta(['name' => 'meta_description', 'content' => e($description)]);
    }


    /**
     * @param string|array $keywords
     *
     * @return Meta
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }

        return $this->addMeta(['name' => 'meta_keywords', 'content' => e($keywords)]);
    }


    /**
     * @param array       $attributes
     * @param null|string $group
     *
     * @return $this
     */
    public function addMeta(array $attributes, $group = null)
    {
        $meta = "<meta" . HTML::attributes($attributes) . " />";

        if ($group === null) {
            if (isset($attributes['name'])) {
                $group = $attributes['name'];
            } else {
                $group = str_random();
            }
        }

        return $this->addToGroup($group, $meta);
    }


    /**
     * @param SocialMediaTaggable $item
     *
     * @return Meta
     */
    public function addSocialTags(SocialMediaTaggable $item)
    {
        return $this
            // Meta tags
            ->setDescription($item->getOgDescription())
            ->setKeywords($item->getOgTags())
            ->setTitle($item->getOgTitle())

            // Open Graph data
            ->addMeta([
                'property' => 'og:title',
                'content'  => $item->getOgTitle(),
                'name' => 'og:title'
            ])->addMeta([
                'property' => 'og:type',
                'content'  => $item->getOgType(),
                'name' => 'og:type'
            ])->addMeta([
                'property' => 'og:url',
                'content'  => $item->getOgUrl(),
                'name' => 'og:url'
            ])->addMeta([
                'property' => 'og:image',
                'content'  => $item->getOgImage(),
                'name' => 'og:image'
            ])->addMeta([
                'property' => 'og:description',
                'content'  => $item->getOgDescription(),
                'name' => 'og:description'
            ])

            // Schema.org markup for Google+
            ->addMeta([
                'itemprop' => 'name',
                'content'  => $item->getOgTitle(),
                'name' => 'google:name'
            ])->addMeta([
                'itemprop' => 'description',
                'content'  => $item->getOgDescription(),
                'name' => 'google:description'
            ])->addMeta([
                'itemprop' => 'image',
                'content'  => $item->getOgImage(),
                'name' => 'google:image'
            ]);
    }


    /**
     * @param string      $filename [default: css/all.css]
     * @param null|string $dependency
     * @param array|null  $attrs
     *
     * @return $this
     */
    public function addCssElixir($filename = 'css/all.css', $dependency = null, array $attrs = null)
    {
        return $this->addCss('elixir.css', elixir($filename), $dependency, $attrs);
    }


    /**
     * @param string      $handle
     * @param string      $src
     * @param null|string $dependency
     * @param null|array  $attrs
     *
     * @return $this
     */
    public function addCss($handle, $src, $dependency = null, array $attrs = null)
    {
        Assets::css($handle, $src, $dependency, $attrs);

        return $this;
    }


    /**
     * @param null|string $handle
     *
     * @return $this
     */
    public function removeCss($handle = null)
    {
        Assets::removeCss($handle);

        return $this;
    }


    /**
     * @param string      $filename [default: js/app.js]
     * @param null|string $dependency
     * @param bool        $footer
     *
     * @return $this
     */
    public function addJsElixir($filename = 'js/app.js', $dependency = null, $footer = false)
    {
        return $this->AddJs('elixir.js', elixir($filename), $dependency, $footer);
    }


    /**
     * @param string      $handle
     * @param string      $src
     * @param null|string $dependency
     * @param bool        $footer
     *
     * @return $this
     */
    public function AddJs($handle, $src, $dependency = null, $footer = false)
    {
        Assets::js($handle, $src, $dependency, $footer);

        return $this;
    }


    /**
     * @param null|string $handle
     *
     * @return $this
     */
    public function removeJs($handle = null)
    {
        Assets::removeJs($handle);

        return $this;
    }


    /**
     * Указание favicon
     *
     * @param string $url
     * @param string $rel
     *
     * @return  $this
     */
    public function setFavicon($url, $rel = 'shortcut icon')
    {
        return $this->addToGroup('icon', '<link rel=":rel" href=":url" type="image/x-icon" />', [
            ':url' => e($url),
            ':rel' => e($rel)
        ]);
    }


    /**
     * @param string      $handle
     * @param string      $content
     * @param array       $params
     * @param null|string $dependency
     *
     * @return $this
     */
    public function addToGroup($handle, $content, $params = [], $dependency = null)
    {
        Assets::group($this->defaultGroup, $handle, strtr($content, $params), $dependency);

        return $this;
    }


    /**
     * @param string|null $handle
     *
     * @return $this
     */
    public function removeFromGroup($handle = null)
    {
        Assets::removeGroup($this->defaultGroup, $handle);

        return $this;
    }


    /**
     * @param string|array $name
     * @param bool         $loadDependencies
     * @param bool         $footer
     *
     * @return $this
     */
    public function addPackage($name, $loadDependencies = false, $footer = false)
    {
        Assets::package($name, $loadDependencies, $footer);

        return $this;
    }


    /**
     * @param string|null $group
     *
     * @return string
     */
    public function build($group = null)
    {
        if (is_null($group)) {
            $group = $this->defaultGroup;
        }

        return Assets::group($group) . Assets::css() . Assets::js();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->build();
    }
}