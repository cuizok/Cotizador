<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Auth/Login.css">

    <title>Login</title>
</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <div class="login-header">
                <h1>Bienvenido</h1>
                <p>Ingresa tus datos para continuar</p>
            </div>


            <form id="loginForm">

                <div class="input-group">
                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input 
                        type="email" 
                        id="correo"
                        name="correo"
                        placeholder="correo@ejemplo.com"
                        required
                    >
                </div>


                <div class="input-group">

                    <label for="password">
                        Contraseña
                    </label>

                    <input 
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >

                </div>


                <button type="submit">
                    Iniciar sesión
                </button>


            </form>


        </div>

    </div>
<div id="toast-container"></div>


<script src="<?= BASE_URL ?>/assets/js/login/login.js"></script>

</body>
</html>