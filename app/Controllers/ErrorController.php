<?php

class ErrorController extends Controller
{
public function notFound()
{
    // Usar el layout público
    $content = __DIR__ . '/../Views/Error/404.php';
    require __DIR__ . '/../Views/Layout/MasterPublic.php';
}
}
