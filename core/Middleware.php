<?php

class Middleware
{
    public static function auth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ Lista de rutas que NO requieren autenticación
        $publicRoutes = [
            '/login',
            '/Login',
            '/Logout',
            '/Home',  // Si el home es público
        ];

        // Obtener la ruta actual sin el base path
        $uri = $_SERVER['REQUEST_URI'];
        $basePath = '/Blackcore/Cotizador/public';
        $uri = str_replace($basePath, '', $uri);
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';

        // ✅ Si es una ruta pública, permitir acceso
        foreach ($publicRoutes as $route) {
            if (strpos($uri, $route) === 0) {
                return;
            }
        }

        if (!isset($_SESSION['usuario_id'])) {
            // Detectar si es una petición AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            // Detectar si es una petición API
            $isApi = strpos($_SERVER['REQUEST_URI'], '/ClienteAll') !== false ||
                     strpos($_SERVER['REQUEST_URI'], '/CotizacionAll') !== false ||
                     strpos($_SERVER['REQUEST_URI'], '/DashboardData') !== false ||
                     strpos($_SERVER['REQUEST_URI'], '/AjustesData') !== false ||
                     strpos($_SERVER['REQUEST_URI'], '/DetalleByCotizacion') !== false;

            if ($isAjax || $isApi) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode([
                    'mensaje' => 'No autorizado',
                    'redirect' => BASE_URL . '/login'
                ]);
                exit;
            } else {
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        }
    }
}