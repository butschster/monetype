<?php

namespace Modules\Support\Helpers;

use Carbon\Carbon;

class Date
{

    const YEAR = 525600;
    const MONTH = 43200;
    const WEEK = 10080;
    const DAY = 1440;
    const HOUR = 60;
    const MINUTE = 1;


    /**
     * @param integer|string|Carbon $date
     * @param string                $format
     *
     * @return string
     */
    public static function format($date = null, $format = null)
    {
        if ($format === null) {
            $format = config('app.date_format', 'd.m.Y H:i');
        }

        if ($date instanceof Carbon) {
            return $date->format($format);
        } else if ( ! is_numeric($date)) {
            $date = strtotime($date);
        }

        if (empty( $date )) {
            return null;
        }

        return date($format, $date);
    }
}