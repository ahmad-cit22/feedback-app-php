<?php

namespace App\Classes;

class Input
{
    public static function get(string $key): mixed
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    public static function isNameValid(string $name): bool
    {
        if (empty($name) || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            return false;
        }
        return true;
    }

    public static function isEmailValid(string $email): bool
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function isPasswordValid(string $password): bool
    {
        if (empty($password) || strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return false;
        }
        return true;
    }

    public static function isConfirmPasswordValid(string $password, string $confirmPassword): bool
    {
        if (empty($confirmPassword) || $password !== $confirmPassword) {
            return false;
        }
        return true;
    }

    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}


