<?php
require_once __DIR__ . '/../../core/PDFHelper.php';

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


public function update() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'mensaje' => 'ID requerido'
        ]);
        return;
    }

    // Obtener los datos del frontend
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar datos básicos
    if (!isset($data['id_cliente']) || !isset($data['titulo'])) {
        http_response_code(400);
        echo json_encode([
            'mensaje' => 'Faltan datos requeridos: id_cliente y titulo'
        ]);
        return;
    }

    $cotizacion = new Cotizacion();

    try {
        $actualizado = $cotizacion->actualizar($id, $data);

        if (!$actualizado) {
            http_response_code(404);
            echo json_encode([
                'mensaje' => 'Cotización no encontrada o sin cambios'
            ]);
            return;
        }

        // Obtener la cotización actualizada para devolverla
        $cotizacionActualizada = $cotizacion->obtenerPorId($id);

        echo json_encode([
            'mensaje' => 'Cotización actualizada correctamente',
            'cotizacion' => $cotizacionActualizada
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'mensaje' => 'Error al actualizar: ' . $e->getMessage()
        ]);
    }

}

  // ============================================
    // API - DASHBOARD
    // ============================================

    public function dashboardData()
    {
        $cotizacion = new Cotizacion();
        $cliente = new Cliente();  // ← NUEVO: instanciar Cliente

        // 1. Obtener todas las cotizaciones
        $cotizaciones = $cotizacion->obtenerTodas();

        // 2. Estadísticas básicas usando el modelo Cliente
        $totalClientes = $cliente->contarActivos();  // ← AHORA usa el modelo
        $totalCotizaciones = count($cotizaciones);

        // 3. Calcular ventas, pendientes, etc.
        $ventas = 0;
        $pendientes = 0;
        $borradores = 0;
        $enviadas = 0;
        $rechazadas = 0;
        $aceptadas = 0;

        foreach ($cotizaciones as $c) {
            if ($c['estatus'] === 'ACEPTADA') {
                $ventas += floatval($c['costo_total'] ?? 0);
                $aceptadas++;
            }

            switch ($c['estatus']) {
                case 'BORRADOR': $borradores++; break;
                case 'ENVIADA': $enviadas++; break;
                case 'RECHAZADA': $rechazadas++; break;
                default: $pendientes++;
            }
        }

        // 4. Datos para gráfica de estatus
        $porEstatus = [];
        if ($borradores > 0) {
            $porEstatus[] = ['label' => 'BORRADOR', 'value' => $borradores, 'color' => '#94a3b8'];
        }
        if ($enviadas > 0) {
            $porEstatus[] = ['label' => 'ENVIADA', 'value' => $enviadas, 'color' => '#3b82f6'];
        }
        if ($aceptadas > 0) {
            $porEstatus[] = ['label' => 'ACEPTADA', 'value' => $aceptadas, 'color' => '#22c55e'];
        }
        if ($rechazadas > 0) {
            $porEstatus[] = ['label' => 'RECHAZADA', 'value' => $rechazadas, 'color' => '#ef4444'];
        }
        if ($pendientes > 0) {
            $porEstatus[] = ['label' => 'PENDIENTE', 'value' => $pendientes, 'color' => '#f59e0b'];
        }

        // 5. Tendencia mensual
        $tendencia = $this->calcularTendenciaMensual($cotizaciones);

        // 6. Últimas cotizaciones (5)
        $recientes = array_slice($cotizaciones, 0, 5);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'totales' => [
                    'clientes' => $totalClientes,
                    'cotizaciones' => $totalCotizaciones,
                    'ventas' => $ventas,
                    'pendientes' => $pendientes + $borradores
                ],
                'por_estatus' => $porEstatus,
                'tendencia_mensual' => $tendencia,
                'recientes' => $recientes
            ]
        ]);
    }

    // ============================================
    // MÉTODOS PRIVADOS
    // ============================================

    /**
     * Calcular tendencia de cotizaciones por mes (últimos 6 meses)
     */
    private function calcularTendenciaMensual($cotizaciones)
    {
        $meses = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M', strtotime("-$i months"));
            $meses[$mes] = 0;
        }

        foreach ($cotizaciones as $c) {
            if (isset($c['created_at'])) {
                $mes = date('Y-m', strtotime($c['created_at']));
                if (isset($meses[$mes])) {
                    $meses[$mes]++;
                }
            }
        }

        return [
            'labels' => $labels,
            'values' => array_values($meses)
        ];
    }


}