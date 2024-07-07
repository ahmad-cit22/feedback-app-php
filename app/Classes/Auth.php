<?php

namespace App\Classes;


class Auth
{
    private string $usersFileName = 'users.txt';

    public function __construct(private User $user)
    {
        $this->user = $user;
    }

    public function register(): void
    {
        // $this->user->register();
        $userData = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => $this->user->password
        ];
    }

    
}