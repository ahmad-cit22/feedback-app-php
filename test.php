<?php

use App\Classes\User;

require_once __DIR__ . '/vendor/autoload.php';

$user = new User('username', 'email', 'password');

echo $user->getUsername() . '<br>';
echo $user->getUniqueShareLink() . '<br>';
