<?php

namespace Modules\Support\Helpers;

use HTML;

class Gravatar
{

    /**
     *
     * @param string  $email
     * @param integer $size
     * @param string  $default
     * @param array   $attributes
     *
     * @return string
     */
    public static function load($email, $size = 100, $default = null, array $attributes = null)
    {
        if (empty( $email )) {
            $email = 'test@test.com';
        }

        if ($default === null) {
            $default = 'mm';
        }

        $hash         = md5(strtolower(trim($email)));
        $query_params = http_build_query([
            'd' => urlencode($default),
            's' => (int) $size,
        ]);

        return HTML::image('http://www.gravatar.com/avatar/' . $hash . '?' . $query_params, null, $attributes);
    }
}