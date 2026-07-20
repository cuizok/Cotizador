<main class="content">

<header class="topbar">

    <h1><?= $title ?? '' ?></h1>

    <div class="user">

        <i class="fa-solid fa-user"></i>

        <span><?= $_SESSION['nombre'] ?? 'Usuario' ?></span>

        <button id="btnLogout"
                title="Cerrar sesión">

            <i class="fa-solid fa-right-from-bracket"></i>

        </button>

    </div>

</header>