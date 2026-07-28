<?php

require_once __DIR__ . '/../../core/PDFHelper.php';

class PDFController extends Controller
{
    public function generarPDF()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die('ID de cotización requerido');
        }

        try {
            $cotizacion = new Cotizacion();
            $data = $cotizacion->obtenerPorId($id);
            $data['detalles'] = $cotizacion->obtenerDetalles($id);

            $ajustes = new Ajustes();
            $ajustesData = $ajustes->obtener();

            $pdf = new PDFHelper();
            $pdf->AliasNbPages();
            $pdf->SetMargins(18, 18, 18);
            $pdf->SetAutoPageBreak(true, 24);
            $pdf->AddPage();

            $this->dibujarEncabezado($pdf, $data, $ajustesData, $id);
            $this->dibujarDatosCliente($pdf, $data);

            if (!empty($data['descripcion'])) {
                $this->dibujarDescripcion($pdf, $data['descripcion']);
            }

            $this->dibujarDetalleServicios($pdf, $data['detalles'] ?? []);
            $this->dibujarTotales($pdf, $data['detalles'] ?? []);
            $this->dibujarNotas($pdf, $ajustesData, $data);

            $pdf->Output('I', 'Cotizacion_' . $id . '.pdf');
            exit;

        } catch (Exception $e) {
            die('Error al generar PDF: ' . $e->getMessage());
        }
    }

 private function dibujarEncabezado($pdf, $data, $ajustesData, $id)
{
    // Barra superior con el color corporativo del panel (#1d2671)
    $pdf->SetFillColor(29, 38, 113); // #1d2671
    $pdf->Rect(0, 0, 210, 10, 'F');

    $pdf->SetY(14);
    $pdf->SetFont('Helvetica', 'B', 18);
    $pdf->SetTextColor(29, 38, 113); // #1d2671
    $pdf->Cell(0, 8, $this->cleanText('COTIZACIÓN'), 0, 1, 'L');

    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(112, 118, 138);
    $pdf->MultiCell(0, 4, $this->cleanText('Propuesta comercial de servicios profesionales, planificación y desarrollo digital'), 0, 'L');

    $pdf->Ln(2);

    // Fondo del cuadro de información con el color de fondo del panel
    $pdf->SetFillColor(248, 249, 252);
    $pdf->SetDrawColor(221, 227, 236);
    $pdf->Rect(14, 32, 182, 24, 'FD');

    $pdf->SetXY(20, 36);
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(40, 4, $this->cleanText('Cotización No.'), 0, 0, 'L');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(72, 81, 99);
    $pdf->Cell(50, 4, $this->cleanText('#' . ($data['id'] ?? $id)), 0, 0, 'L');

    $pdf->SetXY(20, 42);
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(40, 4, $this->cleanText('Fecha'), 0, 0, 'L');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(72, 81, 99);
    $pdf->Cell(50, 4, $this->cleanText(date('d/m/Y')), 0, 0, 'L');

    $pdf->SetXY(110, 36);
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(35, 4, $this->cleanText('Estatus'), 0, 0, 'L');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(72, 81, 99);
    $pdf->Cell(40, 4, $this->cleanText(strtoupper($data['estatus'] ?? 'BORRADOR')), 0, 0, 'L');

    $pdf->SetXY(110, 42);
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(35, 4, $this->cleanText('Remitente'), 0, 0, 'L');
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(72, 81, 99);
    $pdf->MultiCell(0, 4, $this->cleanText($ajustesData['remitente'] ?? 'BlackCore Cotizaciones'), 0, 'L');

    $pdf->SetY(62);
}

    private function dibujarDatosCliente($pdf, $data)
    {
        $this->tituloSeccion($pdf, 'DATOS DEL CLIENTE');

        $pdf->SetFillColor(250, 251, 253);
        $pdf->SetDrawColor(221, 227, 236);
        $pdf->Rect(14, $pdf->GetY() + 2, 182, 24, 'FD');

        $pdf->SetY($pdf->GetY() + 6);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(41, 48, 65);
        $pdf->Cell(30, 5, $this->cleanText('Cliente'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(72, 81, 99);
        $pdf->MultiCell(0, 5, $this->cleanText($data['cliente'] ?? 'Sin cliente'), 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(41, 48, 65);
        $pdf->Cell(30, 5, $this->cleanText('Empresa'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(72, 81, 99);
        $pdf->MultiCell(0, 5, $this->cleanText($data['empresa'] ?? '-'), 0, 'L');

        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(41, 48, 65);
        $pdf->Cell(30, 5, $this->cleanText('Contacto'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(72, 81, 99);
        $pdf->MultiCell(0, 5, $this->cleanText(($data['correo'] ?? '') . ($data['telefono'] ? ' | ' . $data['telefono'] : '')), 0, 'L');

        $pdf->Ln(4); // Aumentado de 2 a 4
    }

    private function dibujarDescripcion($pdf, $descripcion)
    {
        $this->tituloSeccion($pdf, 'DESCRIPCIÓN DEL PROYECTO');

        $pdf->SetFillColor(250, 251, 253);
        $pdf->SetDrawColor(221, 227, 236);
        $pdf->Rect(14, $pdf->GetY() + 2, 182, 24, 'FD');

        $pdf->SetY($pdf->GetY() + 6);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(72, 81, 99);
        $pdf->MultiCell(0, 5, $this->cleanText($descripcion), 0, 'L');
        $pdf->Ln(5); // Aumentado de 3 a 5
    }

 private function dibujarDetalleServicios($pdf, $detalles)
{
    // Espacio adicional antes de la tabla
    $pdf->Ln(14);
    
    $this->tituloSeccion($pdf, 'DETALLE DE SERVICIOS');

    $w1 = 48;
    $w2 = 64;
    $w3 = 26;
    $w4 = 28;

    // Encabezados con el color corporativo #1d2671
    $pdf->SetFont('Helvetica', 'B', 8);
    $pdf->SetFillColor(29, 38, 113); // #1d2671
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetDrawColor(29, 38, 113);
    $pdf->Cell($w1, 8, $this->cleanText('SERVICIO'), 1, 0, 'L', true);
    $pdf->Cell($w2, 8, $this->cleanText('DESCRIPCIÓN'), 1, 0, 'L', true);
    $pdf->Cell($w3, 8, $this->cleanText('TIEMPO'), 1, 0, 'C', true);
    $pdf->Cell($w4, 8, $this->cleanText('COSTO'), 1, 1, 'R', true);

    $pdf->SetDrawColor(221, 227, 236);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(53, 61, 80);

    $subtotal = 0;
    $fila = 0;

    if (empty($detalles)) {
        $pdf->SetFillColor(248, 250, 252);
        $pdf->Cell(166, 8, $this->cleanText('No se registraron servicios en esta cotización.'), 1, 1, 'C', true);
        return;
    }

    foreach ($detalles as $servicio) {
        $costo = floatval($servicio['costo'] ?? 0);
        $tiempo = $servicio['tiempo'] ?? 0;
        $unidad = $servicio['unidad_tiempo'] ?? '';
        $descripcion = $servicio['descripcion'] ?? '-';

        $unidadNormalizada = $this->normalizarUnidad($unidad, $tiempo);

        if (strlen($descripcion) > 40) {
            $descripcion = substr($descripcion, 0, 37) . '...';
        }

        $fill = ($fila % 2 === 0);
        $pdf->SetFillColor($fill ? 255 : 248, $fill ? 255 : 249, $fill ? 255 : 250);
        $pdf->Cell($w1, 7, $this->cleanText($servicio['servicio'] ?? '-'), 1, 0, 'L', $fill);
        $pdf->Cell($w2, 7, $this->cleanText($descripcion), 1, 0, 'L', $fill);
        $pdf->Cell($w3, 7, $this->cleanText($tiempo . ' ' . $unidadNormalizada), 1, 0, 'C', $fill);
        $pdf->Cell($w4, 7, '$' . number_format($costo, 2), 1, 1, 'R', $fill);

        $subtotal += $costo;
        $fila++;
    }

    $pdf->SetTextColor(23, 24, 43);
    $pdf->Ln(10);
}

   private function dibujarTotales($pdf, $detalles)
{
    $subtotal = 0;

    foreach ($detalles as $servicio) {
        $subtotal += floatval($servicio['costo'] ?? 0);
    }

    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;

    $pdf->Ln(2);

    $pdf->SetFillColor(248, 249, 252);
    $pdf->SetDrawColor(221, 227, 236);
    $pdf->Rect(104, $pdf->GetY() + 2, 84, 32, 'FD');

    $pdf->SetY($pdf->GetY() + 6);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(93, 103, 122);
    $pdf->Cell(95, 6, '', 0, 0, 'R');
    $pdf->Cell(25, 6, $this->cleanText('Subtotal:'), 0, 0, 'R');
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(20, 6, '$' . number_format($subtotal, 2), 0, 1, 'R');

    $pdf->SetTextColor(93, 103, 122);
    $pdf->Cell(95, 6, '', 0, 0, 'R');
    $pdf->Cell(25, 6, $this->cleanText('IVA (16%):'), 0, 0, 'R');
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(20, 6, '$' . number_format($iva, 2), 0, 1, 'R');

    // Línea y total con el color corporativo #1d2671
    $pdf->SetDrawColor(29, 38, 113);
    $pdf->Line(130, $pdf->GetY() + 1, 184, $pdf->GetY() + 1);
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetTextColor(29, 38, 113);
    $pdf->Cell(95, 8, '', 0, 0, 'R');
    $pdf->Cell(25, 8, $this->cleanText('TOTAL:'), 0, 0, 'R');
    $pdf->Cell(20, 8, '$' . number_format($total, 2), 0, 1, 'R');

    $pdf->Ln(6);
}
    private function dibujarNotas($pdf, $ajustesData, $data)
    {
        $pdf->SetDrawColor(221, 227, 236);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(6); // Aumentado de 5 a 6

        $this->tituloSeccion($pdf, 'CONDICIONES Y NOTAS');

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(72, 81, 99);

        $notas = [];
        if (!empty($ajustesData['mensajePresentacion'])) {
            $notas[] = $ajustesData['mensajePresentacion'];
        }
        if (!empty($ajustesData['mensajeAgradecimiento'])) {
            $notas[] = $ajustesData['mensajeAgradecimiento'];
        }
        if (!empty($ajustesData['mensajePie'])) {
            $notas[] = $ajustesData['mensajePie'];
        }

        if (empty($notas)) {
            $notas = [
                'La cotización es válida por 15 días.',
                'La fecha de ejecución del servicio se coordinará según disponibilidad.',
                'Cualquier duda o aclaración no dudes en contactarnos.'
            ];
        }

        foreach ($notas as $nota) {
            $pdf->MultiCell(0, 4.5, $this->cleanText('• ' . $nota), 0, 'L');
        }

        $pdf->Ln(4); // Aumentado de 2 a 4
        $tiempoFormateado = $this->formatearTiempoPDF($data['tiempo_total_minutos'] ?? 0);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(122, 130, 148);
        $pdf->Cell(0, 4, $this->cleanText('Tiempo estimado: ' . $tiempoFormateado . '  |  ' . count($data['detalles'] ?? []) . ' servicio(s)'), 0, 1, 'R');
    }

private function tituloSeccion($pdf, $texto)
{
    $y = $pdf->GetY();
    $pdf->SetFillColor(29, 38, 113); // #1d2671
    $pdf->Rect(14, $y + 1, 3, 5, 'F');

    $pdf->SetX(19);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetTextColor(41, 48, 65);
    $pdf->Cell(0, 7, $this->cleanText($texto), 0, 1, 'L');
    $pdf->SetTextColor(23, 24, 43);
    $pdf->Ln(2);
}

    private function cleanText($texto)
    {
        if (is_null($texto)) {
            return '';
        }

        $texto = (string) $texto;

        $texto = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $texto);
        $texto = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $texto);
        $texto = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $texto);
        $texto = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $texto);
        $texto = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $texto);
        $texto = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $texto);
        $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $texto);

        $texto = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $texto);

        if ($texto === false) {
            $texto = @mb_convert_encoding('', 'ISO-8859-1', 'UTF-8');
        }

        return $texto;
    }

    private function formatearTiempoPDF($totalMinutos)
    {
        if (!$totalMinutos || $totalMinutos == 0) return '0 minutos';

        $años = floor($totalMinutos / 525600);
        $totalMinutos %= 525600;
        $meses = floor($totalMinutos / 43200);
        $totalMinutos %= 43200;
        $semanas = floor($totalMinutos / 10080);
        $totalMinutos %= 10080;
        $dias = floor($totalMinutos / 1440);
        $totalMinutos %= 1440;
        $horas = floor($totalMinutos / 60);
        $minutos = $totalMinutos % 60;

        $partes = [];

        if ($años > 0) {
            $partes[] = $años . ' ' . ($años == 1 ? 'año' : 'años');
        }
        if ($meses > 0) {
            $partes[] = $meses . ' ' . ($meses == 1 ? 'mes' : 'meses');
        }
        if ($semanas > 0) {
            $partes[] = $semanas . ' ' . ($semanas == 1 ? 'semana' : 'semanas');
        }
        if ($dias > 0) {
            $partes[] = $dias . ' ' . ($dias == 1 ? 'día' : 'días');
        }
        if ($horas > 0) {
            $partes[] = $horas . ' ' . ($horas == 1 ? 'hora' : 'horas');
        }
        if ($minutos > 0) {
            $partes[] = $minutos . ' ' . ($minutos == 1 ? 'minuto' : 'minutos');
        }

        if (count($partes) > 3) {
            $partes = array_slice($partes, 0, 3);
            $partes[count($partes) - 1] .= '...';
        }

        if (empty($partes)) {
            return $totalMinutos . ' minutos';
        }

        return implode(' ', $partes);
    }

    private function normalizarUnidad($unidad, $cantidad)
    {
        $unidad = strtoupper(trim($unidad));
        
        $unidadesSingular = [
            'AÑO' => 'año',
            'AÑOS' => 'año',
            'ANIO' => 'año',
            'ANIOS' => 'año',
            'MES' => 'mes',
            'MESES' => 'mes',
            'SEMANA' => 'semana',
            'SEMANAS' => 'semana',
            'DIA' => 'día',
            'DIAS' => 'día',
            'HORA' => 'hora',
            'HORAS' => 'hora',
            'MINUTO' => 'minuto',
            'MINUTOS' => 'minuto',
        ];
        
        $raiz = $unidadesSingular[$unidad] ?? strtolower($unidad);
        
        if ($cantidad > 1 || $cantidad == 0) {
            if ($raiz == 'mes') return 'meses';
            if ($raiz == 'año') return 'años';
            if ($raiz == 'semana') return 'semanas';
            if ($raiz == 'día') return 'días';
            if ($raiz == 'hora') return 'horas';
            if ($raiz == 'minuto') return 'minutos';
            return $raiz . 's';
        }
        
        return $raiz;
    }
}