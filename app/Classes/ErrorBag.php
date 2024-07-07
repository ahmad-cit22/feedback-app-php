<?php

namespace App\Classes;

class ErrorBag
{
    private array $errors = [];

    public function __construct()
    {
        //
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $key, string $msg): void
    {
        $this->errors[$key] = $msg;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
