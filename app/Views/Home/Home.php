<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Home/Home.css">
    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title>Dashboard</title>

</head>


<body>


<div class="layout">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">

            <h2>Cotizador</h2>

        </div>


   <nav>

    <a href="#">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
    </a>


    <a href="#">
        <i class="fa-solid fa-users"></i>
        <span>Clientes</span>
    </a>


    <a href="#">
        <i class="fa-solid fa-file-lines"></i>
        <span>Cotizaciones</span>
    </a>


    <a href="#">
        <i class="fa-solid fa-gear"></i>
        <span>Configuración</span>
    </a>

</nav>
    </aside>




    <!-- CONTENIDO -->


    <main class="content">


        <header class="topbar">

            <h1>
                Dashboard
            </h1>


            <div class="user">

                👤 Usuario

            </div>


        </header>



        <!-- TARJETAS -->


        <section class="cards">


            <div class="card">

                <h3>
                    Clientes
                </h3>

                <span>
                    0
                </span>

            </div>



            <div class="card">

                <h3>
                    Cotizaciones
                </h3>

                <span>
                    0
                </span>

            </div>



            <div class="card">

                <h3>
                    Ventas
                </h3>

                <span>
                    $0
                </span>

            </div>



            <div class="card">

                <h3>
                    Pendientes
                </h3>

                <span>
                    0
                </span>

            </div>


        </section>



        <!-- ESPACIO PARA TABLAS / GRAFICAS -->


        <section class="panel">


            <h2>
                Actividad reciente
            </h2>


            <p>
                Aquí irán tablas, gráficas o reportes.
            </p>


        </section>


    </main>


</div>



<script src="<?= BASE_URL ?>/assets/js/Home/Home.js"></script>


</body>

</html>