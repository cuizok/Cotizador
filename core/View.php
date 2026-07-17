<?php

class View
{
    public static function render(string $view, array $data = [])
    {
        extract($data);

        require_once __DIR__ . "/../app/Views/{$view}.php";
    }
}