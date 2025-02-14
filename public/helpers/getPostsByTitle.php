<?php

require(__DIR__ . '/../resources/php/utils.php');

function getPostsByTitle($title)
{
    $filenames = array_diff(scandir(__DIR__ . '/../posts/'), array('..', '.'));
    $filenames = array_filter($filenames, fn ($filename) => $filename === toKebab($title) . '.md');

    return $filenames;
}
