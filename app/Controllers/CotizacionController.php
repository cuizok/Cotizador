<?php

require_once __DIR__.'/../Models/Cotizacion.php';

class CotizacionController
{
    // Funcion para obtener todos los registros
    public function index()
    {

        $cotizacion = new Cotizacion();

        $datos = $cotizacion->obtenerTodas();

        header('Content-Type: application/json');

        echo json_encode(
            $datos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

    }
    // Función para obtener registros por id
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

    $cotizacion = new Cotizacion();

    $registro = $cotizacion->obtenerPorId($id);

    header('Content-Type: application/json');

    if (!$registro) {

        http_response_code(404);

        echo json_encode([
            'mensaje' => 'Cotización no encontrada'
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
        empty($data['id_cliente']) ||
        empty($data['titulo']) ||
        empty($data['descripcion'])
    ) {

        http_response_code(400);

        echo json_encode([
            'mensaje' => 'El cliente, título y descripción son obligatorios.'
        ]);

        return;
    }

    $cotizacion = new Cotizacion();

    $id = $cotizacion->crear($data);

    http_response_code(201);

    echo json_encode([
        'mensaje' => 'Cotización creada correctamente.',
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

    $cotizacion = new Cotizacion();

    $actualizado = $cotizacion->actualizar(
        $id,
        $data
    );

    if (!$actualizado) {

        http_response_code(404);

        echo json_encode([
            'mensaje' => 'Cotizacion no encontrada'
        ]);

        return;
    }

    echo json_encode([
        'mensaje' => 'Cotizacion actualizada correctamente'
    ]);
}

}