<?php

namespace Modules\Users\Traits;

use HTML;
use Image;
use Modules\Support\Helpers\Gravatar;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AvatarTrait
 * @package Modules\Users\Traits
 *
 * @property string $avatar
 */
trait AvatarTrait
{

    /**
     * @var string
     */
    protected $avatarDirectoryName = 'avatars';


    /**
     * @param int $size
     *
     * @return string
     */
    public function getAvatar($size = 50)
    {
        if ( ! empty( $this->avatar )) {
            return $this->getAvatarHtml(['width' => $size . 'px', 'class' => 'img-circle']);
        }

        return $this->getGravatarHTML($size);
    }


    /**
     * @param array $attributes
     *
     * @return string
     */
    public function getAvatarHtml(array $attributes = [])
    {
        return HTML::image("{$this->avatarDirectoryName}/{$this->avatar}", $this->getName(), $attributes);
    }


    /**
     * @param int $size
     *
     * @return string
     */
    public function getGravatarHTML($size = 50)
    {
        return Gravatar::load($this->email, $size, null, ['class' => 'img-circle']);
    }


    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function attachAvatar(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName  = uniqid() . '.' . $extension;
        $path      = $this->getAvatarDirectoryPath();

        if ($file->move($path, $fileName)) {
            $this->deleteAvatar();
            $image = Image::make($path . $fileName);

            $image->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->crop(200, 200);

            $image->orientate();

            $image->save(null, 100);

            $this->avatar = $fileName;
            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function deleteAvatar()
    {
        if ( ! is_null($this->avatar) and file_exists($oldPhoto = $this->getAvatarDirectoryPath() . $this->avatar)) {
            @unlink($oldPhoto);
            $this->avatar = null;

            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return string
     */
    public function getAvatarDirectoryPath()
    {
        return public_path($this->avatarDirectoryName . DIRECTORY_SEPARATOR);
    }
}