<?php

namespace Modules\Users\Traits;

use HTML;
use Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class BackgroundTrait
 * @package Modules\Users\Traits
 *
 * @property string $background
 */
trait BackgroundTrait
{

    /**
     * @var string
     */
    protected $backgroundDirectoryName = 'backgrounds';


    /**
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }


    /**
     * @param array $attributes
     *
     * @return string
     */
    public function getBackgroundHtml(array $attributes = [])
    {
        return HTML::image("{$this->backgroundDirectoryName}/{$this->getBackground()}", $this->getName(), $attributes);
    }


    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function attachBackground(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName  = uniqid() . '.' . $extension;
        $path      = $this->getBackgroundDirectoryPath();

        if ($file->move($path, $fileName)) {

            $this->deletePhoto();

            $this->background = $fileName;

            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function deleteBackground()
    {
        if (
            ! is_null($this->background)
        and
            file_exists($oldPhoto = $this->getBackgroundDirectoryPath() . $this->background)
        ) {
            @unlink($oldPhoto);
            $this->background = null;

            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return string
     */
    public function getBackgroundDirectoryPath()
    {
        return public_path($this->backgroundDirectoryName . DIRECTORY_SEPARATOR);
    }
}