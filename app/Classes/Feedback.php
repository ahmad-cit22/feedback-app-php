<?php

namespace App\Classes;

use Exception;

class Feedback
{
    public function __construct(private string $recipientEmail, private string $feedback) 
    {
        $this->recipientEmail = $recipientEmail;
        $this->feedback = $feedback;
    }

    public function saveData(): void
    {
        $feedbackData = [
            'recipientEmail' => $this->recipientEmail,
            'feedback' => $this->feedback,
            'createdAt' => Utility::dateFormat()
        ];

        $data = json_encode($feedbackData);

        file_put_contents('data/feedbacks.txt', $data . PHP_EOL, FILE_APPEND);
    }
}