<?php

class CotizacionController extends Controller
{


    public function CotizacionView()
    {
        $this->verificarAutenticacion();

        View::render('Cotizaciones/Cotizacion');
    }

    
    public function NuevaCotizacionView()
    {
        $this->verificarAutenticacion();

        View::render('Cotizaciones/Add/NuevaCotizacion');
    }

public function EditCotizacionView()
{
    $idCotizacion = $_GET['id'] ?? 0;

    View::render(
        'Cotizaciones/Edit/EditarCotizacion',
        [
            'idCotizacion' => $idCotizacion
        ]
    );
}

    public function __construct()
    {
        Middleware::auth();
    }

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

if (!$registro) {

    http_response_code(404);

    echo json_encode([
        'mensaje' => 'Cotización no encontrada'
    ]);

    return;
}

// Agregar los servicios de la cotización
$registro['detalles'] = $cotizacion->obtenerDetalles($id);

header('Content-Type: application/json');

echo json_encode(
    $registro,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
);
}

public function store()
{

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (

        empty($data["id_cliente"]) ||

        empty($data["titulo"]) ||

        empty($data["descripcion"])

    ){

        http_response_code(400);

        echo json_encode([
            "mensaje"=>"Todos los datos son obligatorios."
        ]);

        return;

    }

    $cotizacion = new Cotizacion();

    $idCotizacion = $cotizacion->crear($data);

    if(!$idCotizacion){

        http_response_code(500);

        echo json_encode([
            "mensaje"=>"No fue posible crear la cotización."
        ]);

        return;

    }

    if(isset($data["detalles"])){

        foreach($data["detalles"] as $detalle){

            $cotizacion->insertarDetalle(

                $idCotizacion,

                $detalle

            );

        }

    }

    $cotizacion->recalcularCostoTotal(
        $idCotizacion
    );

    $cotizacion->recalcularTiempoTotal(
        $idCotizacion
    );

    echo json_encode([

        "mensaje"=>"Cotización creada correctamente.",

        "id"=>$idCotizacion

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