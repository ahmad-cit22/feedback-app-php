<?php

namespace App\Classes;

class Error
{
    public function __construct(private string $name, private string $message)
    {
        $this->name = $name;
        $this->message = $message;
    }
}
