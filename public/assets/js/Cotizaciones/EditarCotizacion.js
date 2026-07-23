/**
 * ============================================
 * EDITAR COTIZACIÓN - JavaScript
 * ============================================
 */

// ============================================
// ESTADO GLOBAL
// ============================================

let detalleServicios = [];
let clientesList = [];
let cotizacionOriginal = null;

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

document.addEventListener("DOMContentLoaded", async () => {
    await cargarClientes();
    await cargarCotizacion();
    inicializarEventos();
    // Forzar actualización después de cargar
    setTimeout(actualizarResumen, 100);
});

// ============================================
// CARGAR COTIZACIÓN
// ============================================

async function cargarCotizacion() {
    try {
        const response = await fetch(
            `/Blackcore/Cotizador/public/CotizacionById?id=${ID_COTIZACION}`
        );

        if (!response.ok) {
            throw new Error('Error al cargar la cotización');
        }

        const cotizacion = await response.json();
        cotizacionOriginal = cotizacion;

        console.log('Cotización cargada:', cotizacion);

        // --- Información General ---
        document.getElementById("titulo").value = cotizacion.titulo || '';
        document.getElementById("descripcion").value = cotizacion.descripcion || '';

        // --- Cliente ---
        if (cotizacion.id_cliente) {
            document.getElementById("cliente").value = cotizacion.id_cliente;
            const cliente = clientesList.find(c => c.id == cotizacion.id_cliente);
            document.getElementById("lateralCliente").textContent = 
                cliente ? cliente.nombre : "-";
        }

        // --- Título lateral ---
        document.getElementById("lateralTitulo").textContent = 
            cotizacion.titulo || "-";

        // --- Servicios ---
        detalleServicios = cotizacion.detalles || [];

        // Renderizar servicios en el mismo formato que Nueva Cotización
        renderizarServicios();

        // Actualizar todo el dashboard
        actualizarResumen();

    } catch (error) {
        console.error('Error al cargar cotización:', error);
        mostrarToast('Error al cargar la cotización', 'error');
    }
}

// ============================================
// CARGAR CLIENTES
// ============================================

async function cargarClientes() {
    try {
        const response = await fetch(
            "/Blackcore/Cotizador/public/ClienteAll"
        );

        if (!response.ok) {
            throw new Error('Error al cargar clientes');
        }

        clientesList = await response.json();

        const select = document.getElementById("cliente");
        select.innerHTML = '<option value="">Seleccione un cliente...</option>';

        clientesList.forEach(cliente => {
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
// RENDERIZAR SERVICIOS (Mismo formato que Nueva Cotización)
// ============================================

function renderizarServicios() {
    const contenedor = document.getElementById('tbodyServicios');

    if (detalleServicios.length === 0) {
        contenedor.innerHTML = `
            <div class="servicio-vacio">
                <i class="fa-solid fa-layer-group"></i>
                Aún no hay servicios. Da clic en "Agregar servicio" para empezar.
            </div>
        `;
        actualizarContador();
        return;
    }

    contenedor.innerHTML = detalleServicios.map((servicio, index) => `
        <div class="servicio-card" data-id="${servicio.id || Date.now() + index}">
            <div class="servicio-field">
                <span class="mini-label">Servicio</span>
                <input
                    type="text"
                    class="input-servicio"
                    value="${servicio.servicio || ''}"
                    data-index="${index}"
                    data-field="servicio"
                    placeholder="Ej: Desarrollo Web"
                >
            </div>
            <div class="servicio-field">
                <span class="mini-label">Descripción</span>
                <input
                    type="text"
                    class="input-servicio"
                    value="${servicio.descripcion || ''}"
                    data-index="${index}"
                    data-field="descripcion"
                    placeholder="Detalle breve"
                >
            </div>
            <div class="servicio-field">
                <span class="mini-label">Costo ($)</span>
                <input
                    type="number"
                    class="input-servicio"
                    value="${servicio.costo || 0}"
                    data-index="${index}"
                    data-field="costo"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                >
            </div>
            <div class="servicio-field">
                <span class="mini-label">Tiempo</span>
                <input
                    type="number"
                    class="input-servicio"
                    value="${servicio.tiempo || 1}"
                    data-index="${index}"
                    data-field="tiempo"
                    min="0.5"
                    step="0.5"
                >
            </div>
            <div class="servicio-field">
                <span class="mini-label">Unidad</span>
                <select
                    class="input-servicio"
                    data-index="${index}"
                    data-field="unidad_tiempo"
                >
                    ${UNIDADES_KEYS.map(unidad => `
                        <option value="${unidad}" ${servicio.unidad_tiempo === unidad ? 'selected' : ''}>
                            ${UNIDADES_TIEMPO[unidad].label}
                        </option>
                    `).join('')}
                </select>
            </div>
            <button class="btn-eliminar-fila" title="Quitar servicio" onclick="eliminarServicio(${index})">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `).join('');

    // Eventos para actualizar datos en tiempo real
    contenedor.querySelectorAll('.input-servicio').forEach(input => {
        input.addEventListener('input', (e) => {
            const index = parseInt(e.target.dataset.index);
            const field = e.target.dataset.field;
            const value = e.target.type === 'number' ? parseFloat(e.target.value) || 0 : e.target.value;

            if (detalleServicios[index]) {
                detalleServicios[index][field] = value;
                actualizarResumen();
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
                actualizarResumen();
            }
        });
    });

    actualizarContador();
}

function actualizarContador() {
    const contador = document.getElementById('contadorServicios');
    if (contador) contador.textContent = detalleServicios.length;
}

// ============================================
// AGREGAR SERVICIO
// ============================================

function agregarServicio() {
    const nuevoServicio = {
        id: Date.now(),
        servicio: '',
        descripcion: '',
        costo: 0,
        tiempo: 1,
        unidad_tiempo: 'HORAS'
    };

    detalleServicios.push(nuevoServicio);
    renderizarServicios();
    actualizarResumen();

    // Enfocar el primer campo de la nueva card
    requestAnimationFrame(() => {
        const cards = document.querySelectorAll('.servicio-card');
        const ultima = cards[cards.length - 1];
        if (ultima) {
            const input = ultima.querySelector('[data-field="servicio"]');
            if (input) input.focus();
        }
    });

    mostrarToast('Servicio agregado', 'success');
}

// ============================================
// ELIMINAR SERVICIO
// ============================================

function eliminarServicio(index) {
    if (detalleServicios.length <= 1) {
        mostrarToast('Debe haber al menos un servicio', 'warning');
        return;
    }

    if (!confirm('¿Eliminar este servicio?')) {
        return;
    }

    const card = document.querySelector(`.servicio-card[data-index="${index}"]`);
    
    const quitar = () => {
        detalleServicios.splice(index, 1);
        renderizarServicios();
        actualizarResumen();
        mostrarToast('Servicio eliminado', 'info');
    };

    if (card) {
        card.classList.add('removing');
        card.addEventListener('animationend', quitar, { once: true });
    } else {
        quitar();
    }
}

// ============================================
// EVENTOS
// ============================================

function inicializarEventos() {
    // --- Volver ---
    document.getElementById("btnVolver")?.addEventListener("click", () => {
        if (hayCambios()) {
            if (!confirm('Hay cambios sin guardar. ¿Deseas salir?')) {
                return;
            }
        }
        window.location.href = '/Blackcore/Cotizador/public/Cotizaciones';
    });

    // --- Cliente ---
    document.getElementById("cliente").addEventListener("change", () => {
        const cliente = clientesList.find(
            c => c.id == document.getElementById("cliente").value
        );
        document.getElementById("lateralCliente").textContent =
            cliente ? cliente.nombre : "-";
        actualizarResumen();
    });

    // --- Título ---
    document.getElementById("titulo").addEventListener("input", () => {
        document.getElementById("lateralTitulo").textContent =
            document.getElementById("titulo").value.trim() || "-";
        actualizarResumen();
    });

    // --- Descripción ---
    document.getElementById("descripcion").addEventListener("input", () => {
        actualizarResumen();
    });

    // --- Agregar Servicio ---
    document.getElementById("btnAgregarServicio")?.addEventListener("click", agregarServicio);

    // --- Cancelar ---
    document.getElementById("btnCancelar")?.addEventListener("click", () => {
        if (hayCambios()) {
            if (!confirm('Hay cambios sin guardar. ¿Deseas cancelar?')) {
                return;
            }
        }
        window.location.href = '/Blackcore/Cotizador/public/Cotizaciones';
    });

    // --- Actualizar ---
    document.getElementById("btnActualizar")?.addEventListener("click", actualizarCotizacion);
}

// ============================================
// DETECTAR CAMBIOS
// ============================================

function hayCambios() {
    // Verificar si hay cambios en campos simples
    const tituloActual = document.getElementById("titulo").value.trim();
    const descActual = document.getElementById("descripcion").value.trim();
    const clienteActual = document.getElementById("cliente").value;

    if (cotizacionOriginal) {
        if (tituloActual !== (cotizacionOriginal.titulo || '').trim()) return true;
        if (descActual !== (cotizacionOriginal.descripcion || '').trim()) return true;
        if (clienteActual != cotizacionOriginal.id_cliente) return true;
    }

    // Verificar cambios en servicios
    if (cotizacionOriginal && cotizacionOriginal.detalles) {
        if (detalleServicios.length !== cotizacionOriginal.detalles.length) return true;
        
        for (let i = 0; i < detalleServicios.length; i++) {
            const original = cotizacionOriginal.detalles[i];
            const actual = detalleServicios[i];
            if (!original || !actual) return true;
            if (original.servicio !== actual.servicio) return true;
            if (original.descripcion !== actual.descripcion) return true;
            if (Number(original.costo) !== Number(actual.costo)) return true;
            if (Number(original.tiempo) !== Number(actual.tiempo)) return true;
            if (original.unidad_tiempo !== actual.unidad_tiempo) return true;
        }
    }

    return false;
}

// ============================================
// ACTUALIZAR RESUMEN (Dashboard)
// ============================================

function actualizarResumen() {
    let totalCosto = 0;
    let totalMinutos = 0;

    detalleServicios.forEach(servicio => {
        totalCosto += Number(servicio.costo) || 0;
        const minutos = UNIDADES_TIEMPO[servicio.unidad_tiempo]?.minutos || 0;
        totalMinutos += (Number(servicio.tiempo) || 0) * minutos;
    });

    // Actualizar contador de servicios
    const totalServicios = detalleServicios.length;
    document.getElementById("contadorServicios").textContent = totalServicios;

    // Actualizar stats
    document.getElementById("statServicios").textContent = totalServicios;

    document.getElementById("statCosto").textContent = 
        "$" + totalCosto.toLocaleString("es-MX", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById("statTiempo").textContent = 
        formatearTiempo(totalMinutos);

    document.getElementById("statProgreso").textContent = 
        calcularProgreso();

    // Actualizar checklist
    actualizarChecklist();
}

// ============================================
// CALCULAR PROGRESO
// ============================================

function calcularProgreso() {
    let progreso = 0;

    if (document.getElementById("cliente").value) progreso++;
    if (document.getElementById("titulo").value.trim()) progreso++;
    if (detalleServicios.length > 0) progreso++;

    return progreso + "/3";
}

// ============================================
// ACTUALIZAR CHECKLIST
// ============================================

function actualizarChecklist() {
    const checkCliente = document.getElementById("checkCliente");
    const checkTitulo = document.getElementById("checkTitulo");
    const checkServicios = document.getElementById("checkServicios");

    if (checkCliente) {
        checkCliente.classList.toggle("done", document.getElementById("cliente").value != "");
    }
    if (checkTitulo) {
        checkTitulo.classList.toggle("done", document.getElementById("titulo").value.trim() != "");
    }
    if (checkServicios) {
        checkServicios.classList.toggle("done", detalleServicios.length > 0);
    }
}

// ============================================
// FORMATEAR TIEMPO
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

    if (partes.length > 3) {
        return partes.slice(0, 3).join(' ') + '...';
    }

    return partes.join(' ') || '0 días';
}

// ============================================
// ACTUALIZAR COTIZACIÓN (Guardar cambios)
// ============================================

async function actualizarCotizacion() {
    // Validaciones
    const clienteId = document.getElementById("cliente").value;
    const titulo = document.getElementById("titulo").value.trim();

    if (!clienteId) {
        mostrarToast('Selecciona un cliente', 'error');
        document.getElementById("cliente").focus();
        return;
    }

    if (!titulo) {
        mostrarToast('Escribe un título', 'error');
        document.getElementById("titulo").focus();
        return;
    }

    if (detalleServicios.length === 0) {
        mostrarToast('Agrega al menos un servicio', 'error');
        return;
    }

    // Verificar servicios sin nombre
    const serviciosIncompletos = detalleServicios.filter(s => !s.servicio.trim());
    if (serviciosIncompletos.length > 0) {
        mostrarToast('Todos los servicios deben tener un nombre', 'error');
        return;
    }

    // Calcular totales
    let totalCosto = 0;
    let totalMinutos = 0;
    detalleServicios.forEach(s => {
        totalCosto += Number(s.costo) || 0;
        const minutos = UNIDADES_TIEMPO[s.unidad_tiempo]?.minutos || 0;
        totalMinutos += (Number(s.tiempo) || 0) * minutos;
    });

    const data = {
        id_cliente: parseInt(clienteId),
        titulo: titulo,
        descripcion: document.getElementById("descripcion").value.trim() || null,
        detalles: detalleServicios.map(s => ({
            servicio: s.servicio.trim(),
            descripcion: s.descripcion.trim() || null,
            costo: Number(s.costo) || 0,
            tiempo: Number(s.tiempo) || 0,
            unidad_tiempo: s.unidad_tiempo
        }))
    };

    try {
        // Mostrar loading
        const btnActualizar = document.getElementById("btnActualizar");
        const textoOriginal = btnActualizar.innerHTML;
        btnActualizar.innerHTML = '<span class="spinner"></span> Actualizando...';
        btnActualizar.disabled = true;

        const response = await fetch(
            `/Blackcore/Cotizador/public/Update-cotizacion?id=${ID_COTIZACION}`,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }
        );

        const result = await response.json();

        if (response.ok) {
            mostrarToast(result.mensaje || 'Cotización actualizada exitosamente', 'success');
            // Recargar la cotización para actualizar los datos originales
            await cargarCotizacion();
            setTimeout(() => {
                window.location.href = '/Blackcore/Cotizador/public/Cotizaciones';
            }, 1500);
        } else {
            mostrarToast(result.mensaje || 'Error al actualizar', 'error');
            btnActualizar.innerHTML = textoOriginal;
            btnActualizar.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión', 'error');
        const btnActualizar = document.getElementById("btnActualizar");
        btnActualizar.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Actualizar cotización';
        btnActualizar.disabled = false;
    }
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

// ============================================
// FUNCIONES GLOBALES
// ============================================

window.eliminarServicio = eliminarServicio;
window.agregarServicio = agregarServicio;