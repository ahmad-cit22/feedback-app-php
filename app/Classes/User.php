<?php

namespace App\Classes;

use Exception;

class User
{
    private string $feedbackString;
    private array $usersData = [];

    public function __construct(
        private string $name,
        private string $email,
        private string $password
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        $this->loadData();

        $this->setFeedbackString();
    }

    private function setFeedbackString(): void
    {
        do {
            $uniqueStr = substr(md5(uniqid(true)), 0, 6);

            $isUnique = true;

            if ($this->usersData && count($this->usersData) > 0) {
                foreach ($this->usersData as $userData) {
                    $user = json_decode($userData, true);

                    if ($user['feedbackString'] === $uniqueStr) {
                        $isUnique = false;
                        break;
                    }
                }
            }
        } while (!$isUnique);

        $this->feedbackString = $uniqueStr;

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
            'feedbackString' => $this->feedbackString
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
