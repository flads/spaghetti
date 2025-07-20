<?php

namespace Utils;

class Log
{
    const FILENAME = __DIR__ . '/../spaghetti.log';

    public static function putLog(string $message)
    {
        $newFile = file_get_contents(self::FILENAME) . PHP_EOL . $message;

        file_put_contents(self::FILENAME, $newFile);
    }

    public static function info(string $message) {
        self::putLog("[" . date('Y-m-d H:i:s') . "] Info: $message");
    }
    
    public static function error(string $message) {
        self::putLog("[" . date('Y-m-d H:i:s') . "] Error: $message");
    }
}