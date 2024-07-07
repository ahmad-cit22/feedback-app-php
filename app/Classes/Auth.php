<?php

namespace App\Classes;

use Exception;

class Auth
{
    public function __construct()
    {
        //
    }

    public function register(string $name, string $email, string $hashedPassword): void
    {
        $user = new User($name, $email, $hashedPassword);
        $user->saveData();

        Message::flash('success', 'Your account has been created successfully! You can now log in.');
        header('Location: login.php');
        exit;
    }

    public function login(string $email, string $password): bool
    {
        $usersData = $this->getUsersDataJson();
        foreach ($usersData as $user) {
            $user = json_decode($user, true);

            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION);

        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        setcookie(session_name(), '', time() - 3600, '/');
        
        header("Location: login.php");
        exit; 
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
    
    public static function getUser(): array
    {
        return $_SESSION['user'];
    }

    private function getUsersDataJson(): array
    {
        $filePath = 'data/users.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $usersData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        return $usersData;
    }
    
}