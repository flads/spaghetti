<?php

namespace Services;

require(__DIR__ . '/../../app/bootstrap.php');

abstract class Service
{
    public function __construct()
    {
        bootstrap();
    }
}