<?php

require(__DIR__ . '/../../helpers/getPostsByTitle.php');

$form = $_POST;
$errors = [];

$title = $form['title'];
$summary = $form['summary'];
$content = $form['content'];

if (!$title) {
    $errors[] = [
        'field' => 'title',
        'message' => 'Title post is required!',
    ];
}

if (!$summary) {
    $errors[] = [
        'field' => 'summary',
        'message' => 'Summary post is required!',
    ];
}

if (!$content) {
    $errors[] = [
        'field' => 'content',
        'message' => 'Content post is required!',
    ];
}

$postsByTitle = getPostsByTitle($title);
$isTitleInUser = !empty($postsByTitle);

if ($isTitleInUser) {
    $errors[] = [
        'field' => 'title',
        'message' => 'This title is already in use!',
    ];
}

if ($title[0] === '_') {
    $errors[] = [
        'field' => 'title',
        'message' => 'The first character cannot be an underscore!',
    ];
}

if (strlen($summary) > 255) {
    $errors[] = [
        'field' => 'summary',
        'message' => 'The summary can only have 255 characters!',
    ];
}

if (!empty($errors)) {
    http_response_code(422);

    echo json_encode([
        'errors' => $errors
    ]);
    return;
}

$filename = toKebab($title) . '.md';
$filePath = __DIR__ . '/../posts/' . str_replace('/post/', '', $filename);

$postSettings = [
    'title' => '"' . $title . '"',
    'date' => date_format(date_create(), 'Y-m-d H:i:s'),
    'summary' => '"' . $form['summary'] . '"',
    'draft' => $form['draft'] ?? 'false',
    'pinned' => $form['pinned'] ?? 'false',
];

$postContent = '---' . PHP_EOL;

foreach ($postSettings as $key => $value) {
    $postContent .= "$key: $value" . PHP_EOL;
}

$postContent .= '---' . PHP_EOL;
$postContent .= PHP_EOL;
$postContent .= $form['content'];

file_put_contents($filePath, $postContent);
