<?php
// app/Views/Layout/MasterPublic.php

$title = $title ?? 'Cotizador';

require __DIR__ . '/Header.php';
?>

<!-- LAYOUT PÚBLICO (SIN SIDEBAR) -->
<div class="layout-public">
    <main class="content-public">
        <div class="content-public-main">
            <?php require $content; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/Footer.php'; ?>