<?php

namespace App\Classes;

use App\Models\Feedback;

class Utility
{
    /**
     * Constructor for Utility class.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Retrieves the current URL of the PHP script, including query parameters if present.
     *
     * @return string
     */
    public static function getSelfUrl(): string
    {
        $thisUrl = $_SERVER['PHP_SELF'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $thisUrl .= '?' . $_SERVER['QUERY_STRING'];
        }

        return $thisUrl;
    }

    /**
     * Formats a timestamp to 'Y-m-d H:i:s' format. If no timestamp is provided, the current time will be used
     *
     * @param string|null $timestamp the timestamp to format
     * @return string the formatted timestamp
     */
    public static function dateFormat(string $timestamp = null): string
    {
        date_default_timezone_set('Asia/Dhaka');

        if ($timestamp) {
            return date('Y-m-d H:i:s', strtotime($timestamp));
        } else {
            return date('Y-m-d H:i:s');
        }
    }
}



