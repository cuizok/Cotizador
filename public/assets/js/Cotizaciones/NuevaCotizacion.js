/**
 * ============================================
 * NUEVA COTIZACIÓN - JavaScript
 * ============================================
 */

// ============================================
// ESTADO GLOBAL
// ============================================

let detalleServicios = [];
let clientesList = [];
let idClienteSeleccionado = null;

// ============================================
// CONSTANTES DE UNIDADES DE TIEMPO
// ============================================

const UNIDADES_TIEMPO = {
    MINUTOS: { label: 'Minutos', minutos: 1 },
    HORAS: { label: 'Horas', minutos: 60 },
    DIAS: { label: 'Días', minutos: 1440 },
    SEMANAS: { label: 'Semanas', minutos: 10080 },
    MESES: { label: 'Meses', minutos: 43200 },
    ANIOS: { label: 'Años', minutos: 525600 }
};

const UNIDADES_KEYS = Object.keys(UNIDADES_TIEMPO);

// ============================================
// INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    cargarClientes();
    inicializarEventos();
    actualizarVista();
});

// ============================================
// EVENTOS
// ============================================

function inicializarEventos() {
    // Volver
    document.getElementById('btnVolver').addEventListener('click', () => {
        window.history.back();
    });

    // Agregar servicio
    document.getElementById('btnAgregarServicio').addEventListener('click', agregarServicio);

    // Cancelar
    document.getElementById('btnCancelar').addEventListener('click', cancelarCotizacion);

    // Guardar
    document.getElementById('btnGuardar').addEventListener('click', guardarCotizacion);

    // Cliente
    document.getElementById('cliente').addEventListener('change', (e) => {
        idClienteSeleccionado = e.target.value;
        const cliente = clientesList.find(c => c.id == idClienteSeleccionado);
        document.getElementById('lateralCliente').textContent = cliente ? cliente.nombre : 'No seleccionado';
        actualizarVista();
    });

    // Título
    document.getElementById('titulo').addEventListener('input', (e) => {
        document.getElementById('lateralTitulo').textContent = e.target.value || 'Sin título';
        actualizarVista();
    });

    // Descripción
    document.getElementById('descripcion').addEventListener('input', actualizarVista);
}

// ============================================
// CARGAR CLIENTES (API)
// ============================================

async function cargarClientes() {
    try {
        const response = await fetch('/Blackcore/Cotizador/public/ClienteAll');
        const data = await response.json();
        clientesList = data;

        const select = document.getElementById('cliente');
        select.innerHTML = '<option value="">Seleccione un cliente...</option>';

        data.forEach(cliente => {
            const option = document.createElement('option');
            option.value = cliente.id;
            option.textContent = `${cliente.nombre} ${cliente.empresa ? '- ' + cliente.empresa : ''}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar clientes:', error);
        mostrarToast('Error al cargar clientes', 'error');
    }
}

// ============================================
// AGREGAR SERVICIO
// ============================================

function agregarServicio() {
    const nuevoServicio = {
        id: Date.now(), // ID temporal
        servicio: '',
        descripcion: '',
        costo: 0,
        tiempo: 1,
        unidad_tiempo: 'HORAS'
    };

    detalleServicios.push(nuevoServicio);
    renderizarTabla();
    actualizarVista();
}

// ============================================
// ELIMINAR SERVICIO
// ============================================

function eliminarServicio(id) {
    detalleServicios = detalleServicios.filter(s => s.id !== id);
    renderizarTabla();
    actualizarVista();
}

// ============================================
// RENDERIZAR TABLA
// ============================================

function renderizarTabla() {
    const tbody = document.getElementById('tbodyServicios');
    
    if (detalleServicios.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">
                    <i class="fa-solid fa-plus-circle" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                    Haz clic en "Agregar Servicio" para comenzar
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = detalleServicios.map((servicio, index) => `
        <tr data-id="${servicio.id}">
            <td>
                <input 
                    type="text" 
                    class="input-servicio" 
                    value="${servicio.servicio}"
                    data-index="${index}"
                    data-field="servicio"
                    placeholder="Ej: Desarrollo Web"
                >
            </td>
            <td>
                <input 
                    type="text" 
                    class="input-servicio" 
                    value="${servicio.descripcion}"
                    data-index="${index}"
                    data-field="descripcion"
                    placeholder="Descripción del servicio"
                >
            </td>
            <td>
                <input 
                    type="number" 
                    class="input-servicio input-costo" 
                    value="${servicio.costo}"
                    data-index="${index}"
                    data-field="costo"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                >
            </td>
            <td>
                <input 
                    type="number" 
                    class="input-servicio input-tiempo" 
                    value="${servicio.tiempo}"
                    data-index="${index}"
                    data-field="tiempo"
                    min="0.5"
                    step="0.5"
                >
            </td>
            <td>
                <select 
                    class="input-servicio input-unidad" 
                    data-index="${index}"
                    data-field="unidad_tiempo"
                >
                    ${UNIDADES_KEYS.map(unidad => `
                        <option value="${unidad}" ${servicio.unidad_tiempo === unidad ? 'selected' : ''}>
                            ${UNIDADES_TIEMPO[unidad].label}
                        </option>
                    `).join('')}
                </select>
            </td>
            <td>
                <button class="btn-eliminar-fila" onclick="eliminarServicio(${servicio.id})">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');

    // Eventos para actualizar datos en tiempo real
    document.querySelectorAll('.input-servicio').forEach(input => {
        input.addEventListener('input', (e) => {
            const index = parseInt(e.target.dataset.index);
            const field = e.target.dataset.field;
            const value = e.target.type === 'number' ? parseFloat(e.target.value) || 0 : e.target.value;
            
            if (detalleServicios[index]) {
                detalleServicios[index][field] = value;
                actualizarVista();
            }
        });

        input.addEventListener('change', (e) => {
            const index = parseInt(e.target.dataset.index);
            if (e.target.type === 'number' && detalleServicios[index]) {
                const val = parseFloat(e.target.value);
                if (!isNaN(val) && val >= 0) {
                    detalleServicios[index][e.target.dataset.field] = val;
                } else {
                    e.target.value = detalleServicios[index][e.target.dataset.field] || 0;
                }
                actualizarVista();
            }
        });
    });

    // Actualizar contador
    document.getElementById('contadorServicios').textContent = `${detalleServicios.length} servicios`;
}

// ============================================
// ACTUALIZAR VISTA (Cálculos en tiempo real)
// ============================================

function actualizarVista() {
    // Calcular totales
    const totalServicios = detalleServicios.length;
    const costoTotal = detalleServicios.reduce((sum, s) => sum + (parseFloat(s.costo) || 0), 0);
    
    // Calcular tiempo total en minutos
    let totalMinutos = 0;
    detalleServicios.forEach(s => {
        const minutos = UNIDADES_TIEMPO[s.unidad_tiempo]?.minutos || 0;
        totalMinutos += (parseFloat(s.tiempo) || 0) * minutos;
    });

    // Formatear tiempo
    const tiempoFormateado = formatearTiempo(totalMinutos);

    // Actualizar resumen
    document.getElementById('totalServicios').textContent = totalServicios;
    document.getElementById('costoTotal').textContent = `$${costoTotal.toFixed(2)}`;
    document.getElementById('tiempoTotal').textContent = tiempoFormateado;

    // Actualizar panel lateral
    document.getElementById('lateralTotalServicios').textContent = totalServicios;
    document.getElementById('lateralCostoTotal').textContent = `$${costoTotal.toFixed(2)}`;
    document.getElementById('lateralTiempoTotal').textContent = tiempoFormateado;
}

// ============================================
// FORMATEAR TIEMPO (minutos → texto legible)
// ============================================

function formatearTiempo(totalMinutos) {
    if (totalMinutos === 0) return '0 días';

    const años = Math.floor(totalMinutos / 525600);
    totalMinutos %= 525600;

    const meses = Math.floor(totalMinutos / 43200);
    totalMinutos %= 43200;

    const semanas = Math.floor(totalMinutos / 10080);
    totalMinutos %= 10080;

    const dias = Math.floor(totalMinutos / 1440);
    totalMinutos %= 1440;

    const horas = Math.floor(totalMinutos / 60);
    const minutos = Math.floor(totalMinutos % 60);

    const partes = [];
    if (años > 0) partes.push(`${años} año${años > 1 ? 's' : ''}`);
    if (meses > 0) partes.push(`${meses} mes${meses > 1 ? 'es' : ''}`);
    if (semanas > 0) partes.push(`${semanas} semana${semanas > 1 ? 's' : ''}`);
    if (dias > 0) partes.push(`${dias} día${dias > 1 ? 's' : ''}`);
    if (horas > 0 && partes.length < 3) partes.push(`${horas} hora${horas > 1 ? 's' : ''}`);
    if (minutos > 0 && partes.length < 3) partes.push(`${minutos} minuto${minutos > 1 ? 's' : ''}`);

    // Si hay más de 3 partes, mostrar solo las 3 principales
    if (partes.length > 3) {
        return partes.slice(0, 3).join(' ') + '...';
    }

    return partes.join(' ') || '0 días';
}

// ============================================
// CANCELAR
// ============================================

function cancelarCotizacion() {
    if (detalleServicios.length > 0 || 
        document.getElementById('titulo').value || 
        document.getElementById('cliente').value) {
        if (!confirm('¿Estás seguro de cancelar? Se perderán todos los datos.')) {
            return;
        }
    }
    window.location.href = '/Blackcore/Cotizador/public/cotizaciones';
}

// ============================================
// GUARDAR COTIZACIÓN
// ============================================

async function guardarCotizacion() {
    // Validaciones
    const clienteId = document.getElementById('cliente').value;
    const titulo = document.getElementById('titulo').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();

    if (!clienteId) {
        mostrarToast('Selecciona un cliente', 'error');
        document.getElementById('cliente').focus();
        return;
    }

    if (!titulo) {
        mostrarToast('Escribe un título para la cotización', 'error');
        document.getElementById('titulo').focus();
        return;
    }

    if (detalleServicios.length === 0) {
        mostrarToast('Agrega al menos un servicio', 'error');
        return;
    }

    // Verificar que todos los servicios tengan nombre
    const serviciosIncompletos = detalleServicios.filter(s => !s.servicio.trim());
    if (serviciosIncompletos.length > 0) {
        mostrarToast('Todos los servicios deben tener un nombre', 'error');
        return;
    }

    // Calcular totales
    const costoTotal = detalleServicios.reduce((sum, s) => sum + (parseFloat(s.costo) || 0), 0);
    let totalMinutos = 0;
    detalleServicios.forEach(s => {
        const minutos = UNIDADES_TIEMPO[s.unidad_tiempo]?.minutos || 0;
        totalMinutos += (parseFloat(s.tiempo) || 0) * minutos;
    });

    // Preparar datos para enviar
    const data = {
        cliente_id: parseInt(clienteId),
        titulo: titulo,
        descripcion: descripcion,
        costo_total: costoTotal,
        tiempo_total: totalMinutos,
        tiempo_texto: formatearTiempo(totalMinutos),
        detalles: detalleServicios.map(s => ({
            servicio: s.servicio.trim(),
            descripcion: s.descripcion.trim() || null,
            costo: parseFloat(s.costo) || 0,
            tiempo: parseFloat(s.tiempo) || 0,
            unidad_tiempo: s.unidad_tiempo
        }))
    };

    try {
        // Mostrar loading
        const btnGuardar = document.getElementById('btnGuardar');
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<span class="spinner"></span> Guardando...';
        btnGuardar.disabled = true;

        const response = await fetch('/Blackcore/Cotizador/public/cotizacion/guardar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            mostrarToast(result.mensaje || 'Cotización guardada exitosamente', 'success');
            setTimeout(() => {
                window.location.href = '/Blackcore/Cotizador/public/cotizaciones';
            }, 1500);
        } else {
            mostrarToast(result.mensaje || 'Error al guardar la cotización', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión al guardar', 'error');
    } finally {
        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.innerHTML = '<i class="fa-solid fa-save"></i> Guardar Cotización';
        btnGuardar.disabled = false;
    }
}

// ============================================
// TOAST (Notificaciones)
// ============================================

function mostrarToast(mensaje, tipo = 'info') {
    // Eliminar toast anterior si existe
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

    // Auto cerrar después de 3 segundos
    setTimeout(() => {
        if (container.parentNode) {
            container.remove();
        }
    }, 3000);
}

// ============================================
// FUNCIONES GLOBALES (para onclick)
// ============================================

window.eliminarServicio = eliminarServicio;