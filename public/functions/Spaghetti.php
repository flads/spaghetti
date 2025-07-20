<?php

require(__DIR__ . '/../../app/bootstrap.php');

abstract class Spaghetti
{
    public function __construct()
    {
        bootstrap();
    }
}