<!-- ============================================ -->
<!-- SIDEBAR MEJORADO                             -->
<!-- ============================================ -->

<aside class="sidebar" id="sidebar">
    
    <!-- Botón toggle (colapsar/expandir) -->
    <button class="sidebar-toggle" id="sidebarToggle" title="Colapsar menú">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div class="logo-text">
            <span class="logo-title">Cotizador</span>
            <span class="logo-subtitle">BlackCore</span>
        </div>
    </div>


    <!-- Navegación -->
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/Home" class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?= BASE_URL ?>/Cliente" class="nav-link <?= $activePage === 'Cliente' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            <span>Clientes</span>
            <?php if (isset($totalClientes) && $totalClientes > 0): ?>
                <span class="nav-badge"><?= $totalClientes ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?= BASE_URL ?>/Cotizaciones" class="nav-link <?= $activePage === 'cotizaciones' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-lines"></i>
            <span>Cotizaciones</span>
            <?php if (isset($totalCotizaciones) && $totalCotizaciones > 0): ?>
                <span class="nav-badge"><?= $totalCotizaciones ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?= BASE_URL ?>/Ajustes" class="nav-link <?= $activePage === 'Ajustes' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            <span>Ajustes de Presentación</span>
        </a>
    </nav>

        <!-- Perfil de usuario -->
    <div class="sidebar-profile">
        <div class="profile-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="profile-info">
            <span class="profile-name">
                <?= $_SESSION['nombre'] ?? 'Usuario' ?>
            </span>
            <span class="profile-role">
                <?= $_SESSION['rol'] ?? 'Colaborador' ?>
            </span>
        </div>
        <button class="profile-logout" id="btnLogoutSidebar" title="Cerrar sesión">
            <i class="fa-solid fa-right-from-bracket"></i>
        </button>
    </div>


    <!-- Footer del sidebar -->
    <div class="sidebar-footer">
        <span class="footer-version">v1.0.0</span>
        <span class="footer-status">
            <i class="fa-solid fa-circle" style="color: #22c55e; font-size: 8px;"></i>
            Online
        </span>
    </div>

</aside>

<!-- Overlay para móvil -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>