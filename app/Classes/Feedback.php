<?php

namespace App\Classes;

use Exception;

class Feedback
{
    private array $feedbacksData = [];

    public function __construct(private string $recipientEmail, private string $feedback) 
    {
        $this->recipientEmail = $recipientEmail;
        $this->feedback = $feedback;
    }

    public function saveData(): void
    {
        $feedbackData = [
            'recipientEmail' => $this->recipientEmail,
            'feedback' => $this->feedback
        ];

        $data = json_encode($feedbackData);

        file_put_contents('data/feedbacks.txt', $data . PHP_EOL, FILE_APPEND);
    }

    private function loadData(): void
    {
        $filePath = 'data/feedbacks.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $this->feedbacksData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public static function getFeedbacks(string $recipientEmail): array
    {
        self::loadData();

        $userFeedbacks = [];

        foreach (self::$feedbacksData as $feedback) {
            $feedback = json_decode($feedback, true);

            if ($feedback['recipientEmail'] === $recipientEmail) {
                $userFeedbacks[] = $feedback;
            }
        }

        return $userFeedbacks;
    }
}