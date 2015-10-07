<?php

namespace Modules\Support\Helpers;

class String
{

    /**
     * @return string
     */
    public static function uniqueId()
    {
        $uniqId = strtolower(md5(uniqid(rand(), true)));

        return substr($uniqId, 0, 8)
                . '-'
                . substr($uniqId, 8, 4)
                . '-'
                . substr($uniqId, 12, 4)
                . '-'
                . substr($uniqId, 16, 4)
                . '-'
                . substr($uniqId, 20);
    }
}
