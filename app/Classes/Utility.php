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
     * Formats a timestamp into a human-readable date and time string.
     *
     * @param string|null $timestamp The timestamp to format (default is current timestamp).
     * @return string The formatted date and time string.
     */
    public static function dateFormat(string $timestamp = null): string
    {
        date_default_timezone_set('Asia/Dhaka');

        if ($timestamp) {
            return date('d M Y, h:i A', strtotime($timestamp));
        } else {
            return date('Y-m-d H:i:s');
        }
    }
}



