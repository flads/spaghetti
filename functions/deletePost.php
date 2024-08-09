<?php

session_start();

$isLogged = isset($_SESSION["logged"]) && $_SESSION["logged"];
$fileToDelete = $_GET['file_to_delete'];

if ($isLogged && $fileToDelete) {
    rename(
        __DIR__ . '/../posts/' . $fileToDelete . '.md',
        __DIR__ . '/../posts/_trash/' . $fileToDelete . '.md'
    );
}
