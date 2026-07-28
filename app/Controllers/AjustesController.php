<?php

class AjustesController extends Controller
{
    public function AjustesView()
    {
        $this->verificarAutenticacion();

        View::render('Ajustes/Ajustes');
    }

    
    public function show()
    {
        $ajustes = new Ajustes();
        $data = $ajustes->obtener();

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['remitente']) || !isset($data['mensajePresentacion']) ||
            !isset($data['mensajeAgradecimiento']) || !isset($data['mensajePie'])) {
            http_response_code(400);
            echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
            return;
        }

        $ajustes = new Ajustes();
        $resultado = $ajustes->actualizar($data);

        if ($resultado) {
            echo json_encode(['mensaje' => 'Ajustes guardados correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['mensaje' => 'Error al guardar los ajustes']);
        }
    }

}
