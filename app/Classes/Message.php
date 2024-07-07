<?php

namespace App\Classes;

class Message
{
    public function __construct()
    {
        //
    }

    public static function flash(string $type, string $message = null): ?string
    {
        if ($message) {
            $_SESSION['flash'][$type] = $message;
            return null;
        }

        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        
        return $message;
    }

}
