<?php
require_once __DIR__ . '/../../core/Middleware.php';
require_once __DIR__ . '/../Models/Cliente.php';

class ClienteController
{

    public function __construct()
    {
        Middleware::auth();
    }


    public function index()
    {
        $cliente = new Cliente();

        $clientes = $cliente->obtenerTodos();

        header('Content-Type: application/json');

        echo json_encode(
            $clientes,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'ID requerido'
            ]);

            return;
        }

        $cliente = new Cliente();

        $registro = $cliente->obtenerPorId($id);

        header('Content-Type: application/json');

        if (!$registro) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Cliente no encontrado'
            ]);

            return;
        }

        echo json_encode(
            $registro,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    public function store()
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            empty($data['nombre']) ||
            empty($data['correo'])
        ) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'Nombre y correo son obligatorios'
            ]);

            return;
        }

        $cliente = new Cliente();

        $id = $cliente->crear($data);

        http_response_code(201);

        echo json_encode([
            'mensaje' => 'Cliente creado correctamente',
            'id' => $id
        ]);
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'ID requerido'
            ]);

            return;
        }

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $cliente = new Cliente();

        $actualizado = $cliente->actualizar(
            $id,
            $data
        );

        if (!$actualizado) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Cliente no encontrado'
            ]);

            return;
        }

        echo json_encode([
            'mensaje' => 'Cliente actualizado correctamente'
        ]);
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'ID requerido'
            ]);

            return;
        }

        $cliente = new Cliente();

        $resultado = $cliente->desactivar($id);

        if (!$resultado) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Cliente no encontrado'
            ]);

            return;
        }

        echo json_encode([
            'mensaje' => 'Cliente desactivado correctamente'
        ]);
    }
}