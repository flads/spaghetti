<?php

use Helpers\PostsHelper;
use Utils\StringHandler;

require('Spaghetti.php');

class EditPost extends Spaghetti
{
    public function run(): void
    {
        $form = $_POST;
        $errors = [];

        $isAboutPage = $form['filename'] === '_about';

        $date = $form['date'];
        $oldTitle = $form['oldTitle'];
        $title = $form['title'];
        $summary = $form['summary'];
        $content = $form['content'];

        if ($date) {
            if (
                !str_contains($date, '-') ||
                !(strlen($date) == '10') ||
                !is_numeric(str_replace('-', '', $date))
            ) {
                $errors[] = [
                    'field' => 'date',
                    'message' => 'Date format is invalid!',
                ];
            }
        }

        if (!$title) {
            $errors[] = [
                'field' => 'title',
                'message' => 'Title is required!',
            ];
        }

        if (!$content) {
            $errors[] = [
                'field' => 'content',
                'message' => 'Content is required!',
            ];
        }

        if (!$isAboutPage) {
            if (!$summary) {
                $errors[] = [
                    'field' => 'summary',
                    'message' => 'Summary is required!',
                ];
            }

            if (($title !== $oldTitle)) {
                $postsByTitle = PostsHelper::getPostsByTitle($title);
                $isTitleInUser = !empty($postsByTitle);

                if ($isTitleInUser) {
                    $errors[] = [
                        'field' => 'title',
                        'message' => 'This title is already in use!',
                    ];
                }
            }

            if (($title[0] === '_')) {
                $errors[] = [
                    'field' => 'title',
                    'message' => 'The first character cannot be an underscore!',
                ];
            }

            if ((strlen($summary) > 255)) {
                $errors[] = [
                    'field' => 'summary',
                    'message' => 'The summary can only have 255 characters!',
                ];
            }
        }

        if (!empty($errors)) {
            http_response_code(422);

            echo json_encode([
                'errors' => $errors
            ]);
            return;
        }

        $oldFilename = $isAboutPage
            ? '_about.md'
            : StringHandler::toKebab($oldTitle) . '.md';

        $oldFilePath = __DIR__ . '/../posts/' . $oldFilename;

        $postSettings = [
            'title' => '"' . $title . '"',
        ];

        if (!$isAboutPage) {
            $oldFile = file($oldFilePath);

            $postSettings['date'] = $date
                ? date('Y-m-d H:i:s', strtotime($date))
                : mb_substr($oldFile[2], 6, -1);

            $postSettings['summary'] = '"' . $form['summary'] . '"';
            $postSettings['draft'] = $form['draft'] ?? 'false';
            $postSettings['pinned'] = $form['pinned'] ?? 'false';
        }

        $postContent = '---' . PHP_EOL;

        foreach ($postSettings as $key => $value) {
            $postContent .= "$key: $value" . PHP_EOL;
        }

        $postContent .= '---' . PHP_EOL;
        $postContent .= PHP_EOL;
        $postContent .= $form['content'];

        unlink($oldFilePath);

        $newFilename = $isAboutPage
            ? '_about.md'
            : StringHandler::toKebab($title) . '.md';

        $newFilePath = __DIR__ . '/../posts/' . $newFilename;

        file_put_contents($newFilePath, $postContent);

        echo json_encode([
            'filename' => StringHandler::toKebab($title)
        ]);
        return;
    }
}

(new EditPost())->run();
