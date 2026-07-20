<?php


class AuthController extends Controller
{

public function viewLogin()
{
    View::render(
        'Auth/Login',
        [],
        false
    );
}

        public function login()
    {
        session_start();

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            empty($data['correo']) ||
            empty($data['password'])
        ) {

            http_response_code(400);

            echo json_encode([
                'mensaje' => 'Correo y contraseña obligatorios'
            ]);

            return;
        }

        $usuario = new Usuario();

        $registro = $usuario->buscarPorCorreo(
            $data['correo']
        );

        if (!$registro) {

            http_response_code(401);

            echo json_encode([
                'mensaje' => 'Credenciales incorrectas'
            ]);

            return;
        }

        if (
            !password_verify(
                $data['password'],
                $registro['password']
            )
        ) {

            http_response_code(401);

            echo json_encode([
                'mensaje' => 'Credenciales incorrectas'
            ]);

            return;
        }

        $_SESSION['usuario_id'] = $registro['id'];

        $_SESSION['nombre'] = $registro['nombre'];

        $_SESSION['correo'] = $registro['correo'];

        echo json_encode([
            'mensaje' => 'Bienvenido',
            'usuario' => [
                'id' => $registro['id'],
                'nombre' => $registro['nombre']
            ]
        ]);
    }

        public function session()
    {
        session_start();

        if (!isset($_SESSION['usuario_id'])) {

            http_response_code(401);

            echo json_encode([
                'logueado' => false
            ]);

            return;
        }

        echo json_encode([
            'logueado' => true,
            'usuario' => [
                'id' => $_SESSION['usuario_id'],
                'nombre' => $_SESSION['nombre'],
                'correo' => $_SESSION['correo']
            ]
        ]);
    }

        public function logout()
    {
        session_start();

        session_destroy();

        echo json_encode([
            'mensaje' => 'Sesión cerrada correctamente'
        ]);
    }
}