<?php

namespace Modules\Core;

use KodiCMS\Assets\Contracts\SocialMediaTagsInterface;

class CommingSoonSocialTags implements SocialMediaTagsInterface
{
    /**
     * @return string
     */
    public function getOgTitle()
    {
        return trans('core::comingsoon.aboutProject');
    }


    /**
     * @return string
     */
    public function getOgDescription()
    {
        return trans('core::comingsoon.howWorksText');
    }


    /**
     * @return string
     */
    public function getOgImage()
    {
        return url('img\logo.png');
    }


    /**
     * @return string
     */
    public function getOgUrl()
    {
        return url();
    }


    /**
     * @return string
     */
    public function getOgType()
    {
        return 'page';
    }


    /**
     * @return string
     */
    public function getOgPublishedTime()
    {
        return null;
    }


    /**
     * @return string
     */
    public function getOgModifiedTime()
    {
        return null;
    }


    /**
     * @return string
     */
    public function getOgTags()
    {
        return ['monetype', 'money'];
    }
}