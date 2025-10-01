<?php

namespace Services;

use Services\Service;

require('Service.php');

class PinPostService extends Service
{
    public function run(): void
    {
        session_start();

        $isLogged = isset($_SESSION["logged"]) && $_SESSION["logged"];
        $filename = $_GET['filename'];

        if ($isLogged && $filename) {
            $filePath = __DIR__ . '/../posts/' . $filename . '.md';
            $file = file($filePath);

            $oldValue = mb_substr($file[5], 8, -1);
            $newValue = $oldValue === 'true' ? 'false' : 'true';

            $file[5] = "pinned: {$newValue}" . PHP_EOL;

            file_put_contents($filePath, implode('', $file));
        }
    }
}

(new PinPostService())->run();