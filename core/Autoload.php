<?php

spl_autoload_register(function ($class) {

    $paths = [

        __DIR__,
        __DIR__ . '/../app/Controllers',
        __DIR__ . '/../app/Models'

    ];

    foreach ($paths as $path) {

        $file = $path . '/' . $class . '.php';

        if (file_exists($file)) {

            require_once $file;

            return;

        }

    }

});