<?php

$form = $_POST;
$errors = [];

$websiteTitle = $form['website_title'];
$loginUrl = $form['login_url'];
$loginPassword = $form['login_password'];
$githubUser = $form['github_user'];
$linkedinUser = $form['linkedin_user'];

if (!$websiteTitle) {
    $errors[] = [
        'field' => 'website-title',
        'message' => 'Website title is required!',
    ];
}

if (strlen($websiteTitle) > 20) {
    $errors[] = [
        'field' => 'website-title',
        'message' => 'Website title is too long!',
    ];
}

if (!$loginUrl) {
    $errors[] = [
        'field' => 'login-url',
        'message' => 'Login url is required!',
    ];
}

if (strlen($loginUrl) > 20) {
    $errors[] = [
        'field' => 'login-url',
        'message' => 'Login url is too long!',
    ];
}

if (!$loginPassword) {
    $errors[] = [
        'field' => 'login-password',
        'message' => 'Login password is required!',
    ];
}

if (!$githubUser) {
    $errors[] = [
        'field' => 'github-user',
        'message' => 'Github user is required!',
    ];
}

if (!$linkedinUser) {
    $errors[] = [
        'field' => 'linkedin-user',
        'message' => 'Linkedin user is required!',
    ];
}

if (!empty($errors)) {
    http_response_code(422);

    echo json_encode([
        'errors' => $errors
    ]);
    return;
}

$filePath = __DIR__ . '/../.env';

$content = 'WEBSITE_NAME="' . $websiteTitle . '"' . PHP_EOL;
$content .= 'LOGIN_URL="' . $loginUrl . '"' . PHP_EOL;
$content .= 'PASSWORD="' . $loginPassword . '"' . PHP_EOL;
$content .= 'GITHUB_USER="' . $githubUser . '"' . PHP_EOL;
$content .= 'LINKEDIN_USER="' . $linkedinUser . '"' . PHP_EOL;

file_put_contents($filePath, $content);
