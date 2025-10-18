<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;

class TimeHelper
{
    /**
     * Format time from 24-hour to 12-hour format
     *
     * @param string|null $time
     * @return string
     */
    public static function formatTime($time)
    {
        if (!$time) return '';

        if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $time)) {
            try {
                $format = (substr_count($time, ':') === 2) ? 'H:i:s' : 'H:i';
                return Carbon::createFromFormat($format, $time)->format('g:i A');
            } catch (Exception $e) {
                return $time;
            }
        }

        return $time;
    }

    /**
     * Format time for email notifications
     *
     * @param string|null $time
     * @return string
     */
    public static function formatEmailTime($time)
    {
        return self::formatTime($time);
    }
}
