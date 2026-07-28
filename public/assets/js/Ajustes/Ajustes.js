/**
 * ============================================
 * AJUSTES - JavaScript
 * ============================================
 */

let ajustesData = null;

// ============================================
// INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    cargarAjustes();
    inicializarEventos();
});

// ============================================
// CARGAR AJUSTES (API)
// ============================================
// ============================================
// CARGAR AJUSTES (API)
// ============================================

async function cargarAjustes() {
    try {
        const response = await fetch('/Blackcore/Cotizador/public/AjustesData');
        const data = await response.json();
        console.log('Datos cargados:', data);

        if (data) {
            ajustesData = data;

            // Llenar formulario
            document.getElementById('remitente').value = data.remitente || '';
            document.getElementById('mensajePresentacion').value = data.mensajePresentacion || '';
            document.getElementById('mensajeAgradecimiento').value = data.mensajeAgradecimiento || '';
            document.getElementById('mensajePie').value = data.mensajePie || '';

            // Actualizar preview (los elementos SÍ existen)
            actualizarPreview();
        }
    } catch (error) {
        console.error('Error al cargar ajustes:', error);
        mostrarToast('Error al cargar los ajustes', 'error');
    }
}

// ============================================
// ACTUALIZAR PREVIEW EN TIEMPO REAL
// ============================================

function actualizarPreview() {
    const remitente = document.getElementById('remitente').value || '-';
    const presentacion = document.getElementById('mensajePresentacion').value || '-';
    const agradecimiento = document.getElementById('mensajeAgradecimiento').value || '-';
    const pie = document.getElementById('mensajePie').value || '-';

    document.getElementById('previewRemitente').textContent = remitente;
    document.getElementById('previewPresentacion').textContent = presentacion;
    document.getElementById('previewAgradecimiento').textContent = agradecimiento;
    document.getElementById('previewPie').textContent = pie;
}

// ============================================
// EVENTOS
// ============================================

function inicializarEventos() {
    // Volver
    document.getElementById('btnVolver').addEventListener('click', () => {
        window.history.back();
    });

    // Cancelar
    document.getElementById('btnCancelar').addEventListener('click', () => {
        if (hayCambios()) {
            if (!confirm('Hay cambios sin guardar. ¿Deseas cancelar?')) {
                return;
            }
        }
        window.location.href = '/Blackcore/Cotizador/public/Home';
    });

    // Guardar
    document.getElementById('btnGuardar').addEventListener('click', guardarAjustes);

    // Actualizar preview en tiempo real
    document.getElementById('remitente').addEventListener('input', actualizarPreview);
    document.getElementById('mensajePresentacion').addEventListener('input', actualizarPreview);
    document.getElementById('mensajeAgradecimiento').addEventListener('input', actualizarPreview);
    document.getElementById('mensajePie').addEventListener('input', actualizarPreview);

    // Enter para guardar (Ctrl+Enter)
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                guardarAjustes();
            }
        });
    });
}

// ============================================
// DETECTAR CAMBIOS
// ============================================

function hayCambios() {
    if (!ajustesData) return false;

    const remitente = document.getElementById('remitente').value;
    const presentacion = document.getElementById('mensajePresentacion').value;
    const agradecimiento = document.getElementById('mensajeAgradecimiento').value;
    const pie = document.getElementById('mensajePie').value;

    return remitente !== (ajustesData.remitente || '') ||
           presentacion !== (ajustesData.mensajePresentacion || '') ||
           agradecimiento !== (ajustesData.mensajeAgradecimiento || '') ||
           pie !== (ajustesData.mensajePie || '');
}

// ============================================
// GUARDAR AJUSTES
// ============================================

async function guardarAjustes() {
    // Validaciones
    const remitente = document.getElementById('remitente').value.trim();
    const mensajePresentacion = document.getElementById('mensajePresentacion').value.trim();
    const mensajeAgradecimiento = document.getElementById('mensajeAgradecimiento').value.trim();
    const mensajePie = document.getElementById('mensajePie').value.trim();

    if (!remitente) {
        mostrarToast('El remitente es obligatorio', 'error');
        document.getElementById('remitente').focus();
        return;
    }

    if (!mensajePresentacion) {
        mostrarToast('El mensaje de presentación es obligatorio', 'error');
        document.getElementById('mensajePresentacion').focus();
        return;
    }

    if (!mensajeAgradecimiento) {
        mostrarToast('El mensaje de agradecimiento es obligatorio', 'error');
        document.getElementById('mensajeAgradecimiento').focus();
        return;
    }

    if (!mensajePie) {
        mostrarToast('El mensaje de pie de página es obligatorio', 'error');
        document.getElementById('mensajePie').focus();
        return;
    }

    const data = {
        remitente: remitente,
        mensajePresentacion: mensajePresentacion,
        mensajeAgradecimiento: mensajeAgradecimiento,
        mensajePie: mensajePie
    };

    try {
        const btnGuardar = document.getElementById('btnGuardar');
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<span class="spinner"></span> Guardando...';
        btnGuardar.disabled = true;

        const response = await fetch('/Blackcore/Cotizador/public/Update-Ajustes', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            mostrarToast(result.mensaje || 'Ajustes guardados exitosamente', 'success');
            
            // Recargar datos
            await cargarAjustes();
            
            setTimeout(() => {
                window.location.href = '/Blackcore/Cotizador/public/Home';
            }, 1500);
        } else {
            mostrarToast(result.mensaje || 'Error al guardar', 'error');
            btnGuardar.innerHTML = textoOriginal;
            btnGuardar.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión al guardar', 'error');
        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.innerHTML = '<i class="fa-regular fa-floppy-disk"></i> Guardar cambios';
        btnGuardar.disabled = false;
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