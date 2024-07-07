<?php

namespace App\Classes;

use Exception;

class User
{
    private string $uniqueShareLink;
    public array $usersData = [];

    public function __construct(
        private string $name,
        private string $email,
        private string $password
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        $this->loadData();

        $this->setUniqueShareLink();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUniqueShareLink(): ?string
    {
        foreach ($this->usersData as $userData) {
            $user = json_decode($userData, true);

            if ($user['email'] === $this->email) {
                return $user['uniqueLink'];
            } else {
                return null;
            }
        }
    }

    private function setUniqueShareLink(): void
    {
        do {
            $uniqueStr = substr(md5(uniqid(true)), 0, 6);

            $newLink = 'http://localhost/feedback-app-php/feedback/' . $uniqueStr;

            $isUnique = true;

            if ($this->usersData && count($this->usersData) > 0) {
                foreach ($this->usersData as $userData) {
                    $user = json_decode($userData, true);

                    if ($user['feedbackLink'] === $newLink) {
                        $isUnique = false;
                        break;
                    }
                }
            }
        } while (!$isUnique);

        $this->uniqueShareLink = $newLink;

        return;
    }

    private function userExists(): bool
    {
        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);

                if ($user['email'] === $this->email) {
                    return true;
                }
            }
        }

        return false;
    }

    public function saveData(): void
    {
        if ($this->userExists()) {
            throw new Exception("User Already Exists with this Email!");
        }

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'feedbackLink' => $this->uniqueShareLink
        ];

        $data = json_encode($userData);

        file_put_contents('data/users.txt', $data . PHP_EOL, FILE_APPEND);
    }

    private function loadData(): void
    {
        $filePath = 'data/users.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $this->usersData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
