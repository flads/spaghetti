<?php

require(__DIR__ . '/../utils/Log.php');

use Utils\Log;

function bootstrap(): void
{
    registerAutoload();
    setErrorHandler();
}

function registerAutoload(): void
{
    spl_autoload_register(function ($className) {
        $classNameExploded = explode("\\", $className);

        $namespace = $classNameExploded[count($classNameExploded) - 2];
        $className = $classNameExploded[count($classNameExploded) - 1];

        $folder = strtolower($namespace);
        $filename = "$className.php";

        include __DIR__ . "/../$folder/$filename";
    });
}

function setErrorHandler(): void
{
    set_error_handler(function (int $error, string $message, string $filename, int $line) {
        Log::error($message . " at $filename:$line.");

        http_response_code(500);

        echo json_encode([
            'errors' => [
                ['message' => 'Server Error']
            ]
        ]);

        exit();
    });
}