<?php

namespace Helpers;

use Utils\StringHandler;

class PostsHelper
{
    public static function getPostsByTitle(string $title): array
    {
        $filenames = self::getAllPosts();

        return array_filter($filenames, function (string $filename) use ($title) {
            return self::isFilenameEqualsToTitle($filename, $title);
        });
    }

    public static function getAllPosts(): array
    {
        return array_diff(scandir(__DIR__ . '/../public/posts/'), array('..', '.'));
    }

    public static function isFilenameEqualsToTitle(string $filename, string $title): bool
    {
        return $filename === StringHandler::toKebab($title) . '.md';
    }
}