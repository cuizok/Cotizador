<?php
require_once __DIR__ . '/../../core/Middleware.php';
require_once __DIR__.'/../Models/DetalleCotizacion.php';

class DetalleCotizacionController
{

    public function __construct()
    {
        Middleware::auth();
    }
    
    public function store()
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            empty($data['id_cotizacion']) ||
            empty($data['servicio']) ||
            empty($data['descripcion'])
        ) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'El servicio, y la descripción son obligatorios.'
            ]);

            return;
        }

        $cotizacionDetalle = new DetalleCotizacion();

        $id = $cotizacionDetalle->crear($data);

        http_response_code(201);

        echo json_encode([
            'mensaje' => 'Detale de la cotización creada correctamente.',
            'id' => $id
        ]);
    }

    public function show()
        {
        $id_cotizacion = $_GET['id_cotizacion'] ?? null;

        if (!$id_cotizacion) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'ID requerido'
            ]);

            return;
        }

        $cotizacionDetalle = new DetalleCotizacion();

        $registro = $cotizacionDetalle->obtenerPorId($id_cotizacion);

        header('Content-Type: application/json');

        if (!$registro) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Detalle de cotización no encontrada'
            ]);

            return;
        }

        echo json_encode(
            $registro,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
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

        $cotizacionDetalle = new DetalleCotizacion();

        $actualizado = $cotizacionDetalle->actualizar(
            $id,
            $data
        );

        if (!$actualizado) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Detalle de cotización no encontrada'
            ]);

            return;
        }

        echo json_encode([
            'mensaje' => 'Cotizacion actualizada correctamente'
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

        $cotizacionDetalle = new DetalleCotizacion();

        $eliminado = $cotizacionDetalle->eliminar($id);

        if (!$eliminado) {

            http_response_code(404);

            echo json_encode([
                'mensaje' => 'Detalle de cotización no encontrado'
            ]);

            return;
        }

        echo json_encode([
            'mensaje' => 'Detalle de cotización eliminado correctamente'
        ]);
    }

}
