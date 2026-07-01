<?php

require_once '../core/Router.php';

$router = new Router();

require_once '../app/Routes/web.php';

define('BASE_URL', '/Blackcore/Cotizador/public'); // metodo para llamar los assets en las vistas

$router->dispatch();