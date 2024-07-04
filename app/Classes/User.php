<?php

namespace App\Classes;

class User
{
    private const PASSWORD_MIN_LENGTH = 8;
    private const LINK_MIN_LENGTH = 6;

    public function __construct(
        private string $username,
        private string $email,
        private string $password
    ) 
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUniqueShareLink(): string
    {
        $randInt = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $uniqueStr = md5(uniqid($randInt, true));

        $link = 'http://localhost/feedback/' . substr($uniqueStr, 0, self::LINK_MIN_LENGTH);

        return $link;
    }
}
