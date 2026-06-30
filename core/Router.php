<?php

class Router
{
    private array $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

        public function put($uri, $action)
    {
        $this->routes['PUT'][$uri] = $action;
    }

        public function delete($uri, $action)
    {
        $this->routes['DELETE'][$uri] = $action;
    }




public function dispatch()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $method = $_SERVER['REQUEST_METHOD'];

    if (isset($this->routes[$method][$uri])) {

        [$controller, $function] =
            explode('@', $this->routes[$method][$uri]);

        require_once __DIR__ .
            "/../app/Controllers/$controller.php";

        $controller = new $controller();

        $controller->$function();

    } else {

        http_response_code(404);

        header('Content-Type: application/json');

        echo json_encode([
            "mensaje" => "Ruta no encontrada"
        ]);
    }
}
}