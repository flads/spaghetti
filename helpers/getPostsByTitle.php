<?php

require(__DIR__ . '/../utils/utils.php');

function getPostsByTitle($title)
{
    $filenames = array_diff(scandir(__DIR__ . '/../public/posts/'), array('..', '.'));
    $filenames = array_filter($filenames, fn ($filename) => $filename === toKebab($title) . '.md');

    return $filenames;
}
