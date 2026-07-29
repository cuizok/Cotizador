<!-- ============================================ -->
<!-- ERROR 404 - PÁGINA NO ENCONTRADA             -->
<!-- ============================================ -->

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Error/Error.css">

<section class="error-page">
    <div class="error-container">
        <div class="error-content">
            <!-- Icono -->
            <div class="error-icon">
                <i class="fa-regular fa-face-frown"></i>
            </div>
            
            <!-- Código -->
            <h1 class="error-code">404</h1>
            
            <!-- Mensaje -->
            <h2 class="error-title">¡Ups! Página no encontrada</h2>
            
            <p class="error-description">
                La página que estás buscando no existe o ha sido movida.
                <br>
                Verifica la URL o regresa al inicio.
            </p>
            
            <!-- Botones -->
            <div class="error-actions">
                <a href="<?= BASE_URL ?>/Home" class="btn-primary">
                    <i class="fa-solid fa-house"></i>
                    Ir al Inicio
                </a>
                <button onclick="window.history.back()" class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver atrás
                </button>
            </div>
            
            <!-- Info extra -->
            <div class="error-footer">
                <span>Error 404 · Página no encontrada</span>
                <span>|</span>
                <span><?= date('Y') ?> · CuiSoft Cotizaciones</span>
            </div>
        </div>
    </div>
</section>