<?php

namespace Services;

use Services\Service;

require('Service.php');

class DeletePostService extends Service
{
    public function run(): void
    {
        session_start();

        $isLogged = isset($_SESSION["logged"]) && $_SESSION["logged"];
        $fileToDelete = $_GET['file_to_delete'];

        if ($isLogged && $fileToDelete) {
            rename(
                __DIR__ . '/../posts/' . $fileToDelete . '.md',
                __DIR__ . '/../posts/_trash/' . $fileToDelete . '.md'
            );
        }
    }
}

(new DeletePostService())->run();