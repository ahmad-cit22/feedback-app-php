<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Auth;
use App\Classes\ErrorBag;
use App\Classes\Feedback;
use App\Classes\Input;
use App\Classes\Message;
use App\Classes\Utility;

$queryStr = explode('=', $_SERVER['QUERY_STRING'])[1];
$user = Auth::findUser($queryStr);

if ($user === []) {
    Message::flash('error', 'Invalid Link! Please try with a valid link.');
    header('Location: index.php');
    exit;
}

if (Auth::isLoggedIn()) {
    $currentUser = Auth::getCurrentUser();

    if ($currentUser['email'] === $user['email']) {
        Message::flash('error', 'You cannot give feedback to yourself!');
        header('Location: dashboard.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorBag = new ErrorBag();

    $feedback = Input::get('feedback');
    $feedback = Input::sanitizeInput($feedback);

    if (empty($feedback) || $feedback === '') {   
        $errorBag->addError('feedback', 'Feedback cannot be empty!');
    }

    if ($errorBag->hasErrors()) {
        $errors = $errorBag->getErrors();
    } else {
        try {
            $newFeedback = new Feedback($user['email'], $feedback);
            $newFeedback->saveData();

            header("Location: feedback-success.php");
            exit;
        } catch (Exception $e) {
            Message::flash('error', $e->getMessage());
        }
    }
}

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
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="index.php" class="-m-1.5 p-1.5">
                    <span class="sr-only">TruthWhisper</span>
                    <span class="block font-bold text-lg bg-gradient-to-r from-blue-600 via-green-500 to-indigo-400 inline-block text-transparent bg-clip-text">TruthWhisper</span>
                </a>
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
            </div>
        </div>
    </header>

    <main class="">
        <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
            <img src="./images/beams.jpg" alt="" class="absolute top-1/2 left-1/2 max-w-none -translate-x-1/2 -translate-y-1/2" width="1308" />
            <div class="absolute inset-0 bg-[url(./images/grid.svg)] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
            <div class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-lg sm:px-10">
                <div class="mx-auto max-w-xl">
                    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                        <div class="mx-auto w-full max-w-xl text-center">
                            <h1 class="block text-center font-bold text-2xl bg-gradient-to-r from-blue-600 via-green-500 to-indigo-400 inline-block text-transparent bg-clip-text">TruthWhisper</h1>

                            <?php $message = Message::flash('success');
                            if ($message) : ?>
                                <div class="flex justify-center">
                                    <div class="my-3 bg-indigo-100 border border-indigo-200 text-sm text-indigo-700 rounded-lg p-3 text-center" role="alert">
                                        <span class="font-bold"><?= $message; ?></span>
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

                            <h3 class="text-gray-500 my-2">Want to ask something or share a feedback to "<?= $user['name'] ?>"?</h3>
                        </div>

                        <div class="mt-10 mx-auto w-full max-w-xl">
                            <form class="space-y-6" action="<?= Utility::getSelfUrl() ?>" method="POST" novalidate>
                                <div>
                                    <label for="feedback" class="block text-sm font-medium leading-6 text-indigo-700">Don't hesitate, just do it!</label>
                                    <div class="mt-2">
                                        <textarea required name="feedback" id="feedback" cols="30" rows="10" class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-800 sm:text-sm sm:leading-6"></textarea>
                                    </div>

                                    <?php if (isset($errors['feedback'])) : ?>
                                        <p class="text-sm text-red-700 mt-2" id="email-error"><?= $errors['feedback']; ?></p>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
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