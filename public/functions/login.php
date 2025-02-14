<?php

session_start();

$form = $_POST;
$errors = [];

$password = $form["password"];

if (empty($password)) {
    $errors[] = [
        'field' => 'password',
        'message' => 'Password is required!'
    ];
}

if (!empty($password)) {
    $env = parse_ini_file(__DIR__ . '/../../.env');

    if ($password === $env["PASSWORD"]) {
        unset($_SESSION["failed_login_attempts"]);

        $_SESSION["logged"] = true;
        return;
    }

    $_SESSION["failed_login_attempts"] = ($_SESSION["failed_login_attempts"] ?? 0) + 1;

    $attempts = $_SESSION["failed_login_attempts"];

    if ($attempts >= 3) {
        return;
    }

    $errors[] = [
        'field' => 'password',
        'message' => 'Invalid password! ' . (3 - $attempts) . ' attempts left.'
    ];
}

if (!empty($errors)) {
    http_response_code(422);

    echo json_encode([
        'errors' => $errors
    ]);
}
