<!-- app/Views/Home/Home.php -->

<section class="cards">
    <div class="card">
        <h3>Clientes</h3>
        <span id="totalClientes">0</span>
    </div>
    <div class="card">
        <h3>Cotizaciones</h3>
        <span id="totalCotizaciones">0</span>
    </div>
    <div class="card">
        <h3>Ventas</h3>
        <span id="totalVentas">$0</span>
    </div>
    <div class="card">
        <h3>Pendientes</h3>
        <span id="totalPendientes">0</span>
    </div>
</section>

<!-- El panel SOLO se muestra en el Dashboard -->
<section class="panel" id="dashboardPanel">
    <h2>Actividad reciente</h2>
    <!-- Las gráficas y tabla se renderizan con JavaScript -->
</section>

<!-- Ocultar el panel en otras páginas con CSS -->
<style>
    /* Ocultar el panel en páginas que no son dashboard */
    body:not(.page-home) .panel {
        display: none;
    }
</style>

<script>
    // Marcar la página actual
    document.body.classList.add('page-home');
</script>