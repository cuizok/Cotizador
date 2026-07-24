/**
 * ============================================
 * HOME - JavaScript MEJORADO CON DASHBOARD
 * ============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    inicializarSidebar();
    inicializarLogout();
    
    // Detectar si estamos en el dashboard
    const isDashboard = document.querySelector('.cards') !== null;
    
    if (isDashboard) {
        cargarDashboard();
    }
});

// ============================================
// SIDEBAR
// ============================================

function inicializarSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const topbarToggle = document.getElementById('topbarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
    }

    if (topbarToggle) {
        topbarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            if (overlay) {
                overlay.classList.toggle('show');
            }
        });
    }

    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true' && window.innerWidth > 1024) {
        sidebar.classList.add('collapsed');
    }

    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            const isClickInside = sidebar.contains(e.target);
            const isToggleClick = topbarToggle && topbarToggle.contains(e.target);
            
            if (!isClickInside && !isToggleClick && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                if (overlay) {
                    overlay.classList.remove('show');
                }
            }
        }
    });

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('open');
                if (overlay) {
                    overlay.classList.remove('show');
                }
            }
        }, 250);
    });
}

// ============================================
// LOGOUT
// ============================================

function inicializarLogout() {
    const btnLogoutSidebar = document.getElementById('btnLogoutSidebar');
    const btnLogoutTopbar = document.getElementById('btnLogout');

    const handleLogout = async function(e) {
        e.preventDefault();
        
        if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            return;
        }

        try {
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            const response = await fetch('/Blackcore/Cotizador/public/Logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                mostrarToast(data.mensaje || 'Sesión cerrada exitosamente', 'success');
                setTimeout(() => {
                    window.location.href = '/Blackcore/Cotizador/public/login';
                }, 1000);
            } else {
                mostrarToast(data.mensaje || 'Error al cerrar sesión', 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i>';
            }
        } catch (error) {
            console.error('Error en logout:', error);
            mostrarToast('Error de conexión al cerrar sesión', 'error');
            this.disabled = false;
            this.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i>';
        }
    };

    if (btnLogoutSidebar) {
        btnLogoutSidebar.addEventListener('click', handleLogout);
    }

    if (btnLogoutTopbar) {
        btnLogoutTopbar.addEventListener('click', handleLogout);
    }
}

// ============================================
// DASHBOARD - SOLO PARA LA PÁGINA DE INICIO
// ============================================

async function cargarDashboard() {
    try {
        const response = await fetch('/Blackcore/Cotizador/public/DashboardData');
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Actualizar cards
            const cards = document.querySelectorAll('.card span');
            if (cards.length >= 4) {
                cards[0].textContent = data.totales.clientes || 0;
                cards[1].textContent = data.totales.cotizaciones || 0;
                cards[2].textContent = '$' + (data.totales.ventas || 0).toLocaleString('es-MX', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                cards[3].textContent = data.totales.pendientes || 0;
            }
            
            // Renderizar gráficas SOLO si estamos en el dashboard
            const panel = document.querySelector('.panel');
            if (panel) {
                // Limpiar contenido anterior del panel (excepto el título)
                const titulo = panel.querySelector('h2');
                panel.innerHTML = '';
                if (titulo) {
                    panel.appendChild(titulo);
                }
                
                // Crear contenedor para gráficas
                const graficasContainer = document.createElement('div');
                graficasContainer.id = 'graficasContainer';
                graficasContainer.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;';
                panel.appendChild(graficasContainer);
                
                // Renderizar gráfica de estatus
                if (data.por_estatus && data.por_estatus.length > 0) {
                    renderizarGraficaEstatus(data.por_estatus, graficasContainer);
                }
                
                // Renderizar gráfica de tendencia
                if (data.tendencia_mensual) {
                    renderizarGraficaTendencia(data.tendencia_mensual, graficasContainer);
                }
                
                // Renderizar tabla de cotizaciones recientes
                if (data.recientes && data.recientes.length > 0) {
                    renderizarRecientes(data.recientes, panel);
                }
            }
        }
    } catch (error) {
        console.error('Error al cargar dashboard:', error);
        mostrarToast('Error al cargar los datos del dashboard', 'error');
    }
}

// ============================================
// GRÁFICA DE ESTATUS
// ============================================

function renderizarGraficaEstatus(data, container) {
    const total = data.reduce((a, b) => a + b.value, 0) || 1;
    
    const estatusHTML = `
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="font-size: 14px; color: #0f172a; margin-bottom: 12px;">
                <i class="fa-solid fa-chart-pie" style="color: #3b82f6; margin-right: 8px;"></i>
                Cotizaciones por estatus
            </h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                ${data.map(item => `
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: ${item.color}; flex-shrink: 0;"></div>
                        <span style="flex: 1; font-size: 13px; color: #1e293b;">${item.label}</span>
                        <span style="font-weight: 600; color: #0f172a;">${item.value}</span>
                        <span style="font-size: 12px; color: #94a3b8;">(${Math.round(item.value / total * 100)}%)</span>
                    </div>
                `).join('')}
            </div>
            <div style="margin-top: 12px; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; display: flex;">
                ${data.map(item => `
                    <div style="flex: ${item.value}; background: ${item.color}; height: 100%;"></div>
                `).join('')}
            </div>
        </div>
    `;
    
    container.innerHTML += estatusHTML;
}

// ============================================
// GRÁFICA DE TENDENCIA
// ============================================

function renderizarGraficaTendencia(data, container) {
    const labels = data.labels || [];
    const values = data.values || [];
    const max = Math.max(...values, 1);
    
    const tendenciaHTML = `
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="font-size: 14px; color: #0f172a; margin-bottom: 12px;">
                <i class="fa-solid fa-chart-bar" style="color: #8b5cf6; margin-right: 8px;"></i>
                Cotizaciones por mes
            </h3>
            <div style="display: flex; align-items: flex-end; gap: 6px; height: 120px; padding: 4px 0;">
                ${values.map(val => {
                    const height = max > 0 ? (val / max) * 100 : 0;
                    return `
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end;">
                            <span style="font-size: 11px; font-weight: 600; color: #0f172a;">${val}</span>
                            <div style="width: 100%; background: linear-gradient(180deg, #8b5cf6, #3b82f6); border-radius: 4px 4px 0 0; height: ${height}%; min-height: 4px; transition: height 0.5s ease;"></div>
                        </div>
                    `;
                }).join('')}
            </div>
            <div style="display: flex; justify-content: space-around; margin-top: 4px;">
                ${labels.map(label => `
                    <span style="font-size: 11px; color: #94a3b8;">${label}</span>
                `).join('')}
            </div>
        </div>
    `;
    
    container.innerHTML += tendenciaHTML;
}

// ============================================
// TABLA DE COTIZACIONES RECIENTES
// ============================================

function renderizarRecientes(cotizaciones, panel) {
    const getBadgeText = (estatus) => {
        const texts = {
            'BORRADOR': 'Borrador',
            'ENVIADA': 'Enviada',
            'ACEPTADA': 'Aceptada',
            'RECHAZADA': 'Rechazada',
            'PENDIENTE': 'Pendiente'
        };
        return texts[estatus] || estatus;
    };
    
    const getBadgeStyle = (estatus) => {
        const styles = {
            'ACEPTADA': { bg: '#dcfce7', color: '#166534' },
            'RECHAZADA': { bg: '#fee2e2', color: '#991b1b' },
            'ENVIADA': { bg: '#dbeafe', color: '#1e40af' },
            'BORRADOR': { bg: '#fef3c7', color: '#92400e' },
            'PENDIENTE': { bg: '#fef3c7', color: '#92400e' }
        };
        return styles[estatus] || { bg: '#f1f5f9', color: '#475569' };
    };
    
    const tablaHTML = `
        <div style="margin-top: 24px;">
            <h3 style="font-size: 14px; color: #0f172a; margin-bottom: 12px;">
                <i class="fa-regular fa-clock" style="color: #f59e0b; margin-right: 8px;"></i>
                Últimas cotizaciones
            </h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #64748b;">ID</th>
                            <th style="padding: 10px 12px; text-align: left; font-weight: 600; color: #64748b;">Cliente</th>
                            <th style="padding: 10px 12px; text-align: right; font-weight: 600; color: #64748b;">Total</th>
                            <th style="padding: 10px 12px; text-align: center; font-weight: 600; color: #64748b;">Estatus</th>
                            <th style="padding: 10px 12px; text-align: center; font-weight: 600; color: #64748b;">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${cotizaciones.map(c => {
                            const style = getBadgeStyle(c.estatus);
                            return `
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 10px 12px; font-weight: 500;">#${c.id}</td>
                                    <td style="padding: 10px 12px;">${c.cliente || 'Sin cliente'}</td>
                                    <td style="padding: 10px 12px; text-align: right; font-weight: 500;">$${Number(c.costo_total || 0).toFixed(2)}</td>
                                    <td style="padding: 10px 12px; text-align: center;">
                                        <span style="display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; background: ${style.bg}; color: ${style.color};">
                                            ${getBadgeText(c.estatus)}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 12px; text-align: center; font-size: 12px; color: #64748b;">
                                        ${c.created_at ? new Date(c.created_at).toLocaleDateString('es-MX') : '-'}
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    panel.innerHTML += tablaHTML;
}

// ============================================
// TOAST
// ============================================

function mostrarToast(mensaje, tipo = 'info') {
    const existing = document.querySelector('.toast-container');
    if (existing) existing.remove();

    const container = document.createElement('div');
    container.className = 'toast-container';

    const toast = document.createElement('div');
    toast.className = `toast toast-${tipo}`;
    
    const iconos = {
        success: 'fa-solid fa-check-circle',
        error: 'fa-solid fa-exclamation-circle',
        warning: 'fa-solid fa-triangle-exclamation',
        info: 'fa-solid fa-info-circle'
    };

    toast.innerHTML = `
        <i class="${iconos[tipo] || iconos.info}"></i>
        <span>${mensaje}</span>
    `;

    container.appendChild(toast);
    document.body.appendChild(container);

    setTimeout(() => {
        if (container.parentNode) {
            container.remove();
        }
    }, 3000);
}