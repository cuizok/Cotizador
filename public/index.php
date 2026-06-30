<?php

require_once '../core/Router.php';

$router = new Router();

require_once '../app/Routes/web.php';

$router->dispatch();