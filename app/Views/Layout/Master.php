<?php
// app/Views/Layout/Master.php

// Definir página activa para el sidebar
$activePage = $activePage ?? 'home';

// Incluir header (abre HTML y body)
require __DIR__ . '/Header.php';
?>

<!-- LAYOUT PRINCIPAL -->
<div class="layout">

    <!-- SIDEBAR -->
    <?php require __DIR__ . '/Sidebar.php'; ?>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="content">
        
        <!-- TOPBAR (Ahora está dentro del content, en la parte superior) -->
        <?php require __DIR__ . '/Topbar.php'; ?>

        <!-- CONTENIDO DE LA PÁGINA -->
        <div class="content-main">
            <?php require $content; ?>
        </div>

    </main>

</div>

<?php
// Incluir footer (cierra body y html)
require __DIR__ . '/Footer.php';
?>