<!-- ============================================ -->
<!-- TOPBAR MEJORADO                              -->
<!-- ============================================ -->

<header class="topbar">
    <div class="topbar-left">
        <!-- Botón toggle para móvil -->
        <button class="topbar-toggle" id="topbarToggle" title="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>

    <div class="topbar-right">
        <!-- Notificaciones -->
        <button class="topbar-btn" title="Notificaciones">
            <i class="fa-regular fa-bell"></i>
            <span class="btn-badge">3</span>
        </button>

        <!-- Ayuda -->
        <button class="topbar-btn" title="Ayuda">
            <i class="fa-regular fa-circle-question"></i>
        </button>

        <!-- Perfil (visible solo en móvil) -->
        <div class="topbar-user mobile-only">
            <i class="fa-solid fa-user"></i>
            <span><?= $_SESSION['nombre'] ?? 'Usuario' ?></span>
        </div>
    </div>
</header>