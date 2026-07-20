<?php

class View
{

    public static function render(
        string $view,
        array $data = [],
        bool $layout = true
    )
    {

        extract($data);

        if (!$layout) {

            require __DIR__ . "/../app/Views/{$view}.php";

            return;

        }

        $content = __DIR__ . "/../app/Views/{$view}.php";

        require __DIR__ . "/../app/Views/Layout/Master.php";

    }

}