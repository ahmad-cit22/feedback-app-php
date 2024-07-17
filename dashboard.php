<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Auth;
use App\Classes\Message;
use App\Classes\User;
use App\Classes\Utility;

Auth::check();

$user = Auth::getCurrentUser();

$userObj = new User($user['name'], $user['email'], $user['password'], false);
$feedbacks = $userObj->getFeedbacks();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruthWhisper - Anonymous Feedback App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <header class="bg-white">
        <nav class="flex items-center justify-between p-6 lg:px-16" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="index.php" class="-m-1.5 p-1.5">
                    <span class="sr-only">TruthWhisper</span>
                    <span class="block font-bold text-lg bg-gradient-to-r from-blue-600 via-green-500 to-indigo-400 inline-block text-transparent bg-clip-text">TruthWhisper</span>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <span class="text-sm font-semibold leading-6 text-indigo-600 border-b border-indigo-400 hover:border-indigo-600 cursor-pointer rounded-xl px-2 py-1 text-center hover:text-indigo-800"><?= $user['name'] ?></span>

                <span class="ms-3 text-xs font-semibold leading-6 text-red-600 border-b border-red-400 hover:border-red-600 cursor-pointer rounded-xl px-2 py-1 text-center hover:text-red-800"><a href="logout.php">Logout</a></span>
            </div>
        </nav>
        <!-- Mobile menu, show/hide based on menu open state. -->
        <div class="lg:hidden" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on slide-over state. -->
            <div class="fixed inset-0 z-10"></div>
            <div class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="index.php" class="-m-1.5 p-1.5">
                        <span class="sr-only">TruthWhisper</span>
                        <span class="block font-bold text-xl bg-gradient-to-r from-blue-600 via-green-500 to-indigo-400 inline-block text-transparent bg-clip-text">TruthWhisper</span>
                    </a>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="py-6">
                            <span class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"><?= $user['name'] ?></span>

                            <span class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-red-900 hover:bg-red-50"><a href="logout.php">Logout</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="">
        <div class="relative flex min-h-screen overflow-hidden bg-gray-50 py-6 sm:py-12">
            <img src="./images/beams.jpg" alt="" class="absolute top-1/2 left-1/2 max-w-none -translate-x-1/2 -translate-y-1/2" width="1308" />
            <div class="absolute inset-0 bg-[url(./images/grid.svg)] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>

            <div class="relative mx-auto" style="width: 95%;">

                <?php $message = Message::flash('success');
                if ($message) : ?>
                    <div class="flex justify-center">
                        <div class="my-3 bg-green-100 border border-green-200 text-sm text-green-700 rounded-lg p-3 text-center" role="alert">
                            <i class="fa-solid fa-circle-check"></i> <span class="font-bold"><?= $message; ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $message = Message::flash('error');
                if ($message) : ?>
                    <div class="flex justify-center">
                        <div class="mt-3 bg-red-100 border border-red-200 text-sm text-red-700 rounded-lg p-3 text-center" role="alert">
                            <span class="font-bold"><?= $message; ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex justify-end">
                    <a href="http://127.0.0.1/feedback-app-php/feedback-<?= $user['feedbackString'] ?>" title="Your Feedback form link that can be shared to others."><span class="block text-gray-600 font-mono border border-gray-400 rounded-xl px-2 py-1 text-center hover:text-indigo-800 hover:border-indigo-600 cursor-pointer my-3 hover:underline mr-10 text-sm">Your Feedback form link: <strong>http://127.0.0.1/feedback-app-php/feedback-<?= $user['feedbackString'] ?></strong></span></a>
                </div>
                <div class="px-10 py-3">
                    <h1 class="text-xl text-indigo-800 text-bold my-10">Received feedbacks</h1>

                    <?php if (count($feedbacks) < 1 || !$feedbacks) : ?>
                        <div class="flex justify-center">
                            <div class="mt-3 bg-indigo-100 border border-indigo-200 text-sm text-indigo-700 rounded-lg p-3 text-center" role="alert">
                                <span class="font-bold">No feedbacks yet.</span>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <?php foreach ($feedbacks as $key => $feedback) : ?>
                                <div class="relative space-x-3 rounded-lg border border-gray-300 bg-white px-7 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                                    <div class="focus:outline-none">
                                        <p class="text-xs font-medium text-gray-400 mt-1 mb-2"><?= $key + 1 ?></p>
                                        <p class="text-gray-800 break-words test-justify"><?= $feedback['feedback'] ?></p>
                                        <p class="text-gray-500 text-sm mt-3 text-right font-mono"><?= Utility::dateFormat($feedback['createdAt']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-white">
        <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center justify-center lg:px-8">
            <p class="text-center text-xs leading-5 text-gray-500">&copy; 2024 TruthWhisper, Inc. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>