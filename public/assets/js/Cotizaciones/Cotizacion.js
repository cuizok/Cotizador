let detalleServicios = [];

const UNIDADES_TIEMPO = {
    MINUTOS: { label:"Minutos", minutos:1 },
    HORAS:{ label:"Horas", minutos:60 },
    DIAS:{ label:"Días", minutos:1440 },
    SEMANAS:{ label:"Semanas", minutos:10080 },
    MESES:{ label:"Meses", minutos:43200 },
    ANIOS:{ label:"Años", minutos:525600 }
};

// ============================================
// EVENTO DEL BOTÓN PDF
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const btnPDF = document.getElementById('btnGenerarPDF');
    if (btnPDF) {
        btnPDF.addEventListener('click', function() {
            const id = this.dataset.idCotizacion;
            if (id) {
                // ✅ Abrir en nueva pestaña
                window.open(`/Blackcore/Cotizador/public/GenerarPDF?id=${id}`, '_blank');
                // ⬆️ Esto ya abre en nueva pestaña
            } else {
                mostrarToast('No se encontró la cotización', 'error');
            }
        });
    }
});
document.addEventListener("DOMContentLoaded", () => {

   cargarCotizaciones();

});


document
.getElementById("btnNuevo")
.addEventListener("click", () => {

    window.location.href =
    "/Blackcore/Cotizador/public/NuevaCotizacion";

});



document
.getElementById("btnCancelarEliminar")
.addEventListener(
    "click",
    cerrarModalEliminar
);

document
.getElementById("btnConfirmarEliminar")
.addEventListener(
    "click",
    confirmarEliminarCliente
);






async function cargarCotizaciones(){

    try{

        const response = await fetch(
            "/Blackcore/Cotizador/public/CotizacionAll"
        );

        const cotizaciones = await response.json();

        const tbody =
        document.getElementById("tbodyCotizacion");

        tbody.innerHTML = "";

        cotizaciones.forEach(cotizaciones=>{

            tbody.innerHTML += `

                <tr>

                    <td>${cotizaciones.id}</td>

                    <td>${cotizaciones.titulo}</td>

                    <td>${cotizaciones.cliente}</td>


                    <td>

                        <span class="
                            badge
                            ${cotizaciones.estatus === 'ACEPTADA'
                                ? 'badge-success'
                                : 'badge-danger'}
                        ">

                            ${cotizaciones.estatus}

                        </span>

                    </td>

                    <td>

                        <button
                            onclick="cargarCotizacionbyId(${cotizaciones.id})">

                             <i class="fa-solid fa-eye"></i>

                        </button>

                        <button
                            onclick="editarCotizacion(${cotizaciones.id})">

                            <i class="fa-solid fa-pen icon-editar"></i>

                        </button>

                        <button
                            onclick="eliminarCotizacion(${cotizaciones.id})">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </td>

                </tr>

            `;

        });

    }catch(error){

        console.error(error);

        mostrarToast(
            "Error al cargar cotizaciones",
            "error"
        );

    }

}

// ============================================
// FORMATEAR TIEMPO (minutos → texto legible)
// ============================================

function formatearTiempo(totalMinutos) {
    if (!totalMinutos || totalMinutos === 0) return '0 minutos';

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

    return partes.join(' ') || '0 minutos';
}

function abrirPanelDetalle(){

    document
    .getElementById("panelDetalle")
    .classList.add("show");

    document
    .getElementById("overlayDetalle")
    .classList.add("show");

}

function cerrarPanelDetalle(){

    document
    .getElementById("panelDetalle")
    .classList.remove("show");

    document
    .getElementById("overlayDetalle")
    .classList.remove("show");

}

document
.getElementById("cerrarPanelDetalle")
.addEventListener("click",cerrarPanelDetalle);

document
.getElementById("overlayDetalle")
.addEventListener("click",cerrarPanelDetalle);

async function cargarCotizacionbyId(id){
    try{
        const response = await fetch(
            `/Blackcore/Cotizador/public/CotizacionById?id=${id}`
        );

        const cotizacion = await response.json();
        detalleServicios = cotizacion.detalles;

        const contenido = document.getElementById("contenidoDetalle");

        contenido.innerHTML = `

        <div class="detalle-seccion">

            <div class="detalle-titulo">

                Información General

            </div>

            <h2>${cotizacion.titulo}</h2>

            <p>

                ${cotizacion.descripcion}

            </p>

        </div>

        <div class="detalle-seccion">

            <div class="detalle-titulo">

                Cliente

            </div>

            <div class="card-cliente">

                <h3>

                    ${cotizacion.cliente}

                </h3>

                <p>

                    <i class="fa-solid fa-building"></i>

                    ${cotizacion.empresa}

                </p>

                <p>

                    <i class="fa-solid fa-envelope"></i>

                    ${cotizacion.correo}

                </p>

                <p>

                    <i class="fa-solid fa-phone"></i>

                    ${cotizacion.telefono}

                </p>

            </div>

        </div>

        <div class="detalle-seccion">

            <div class="detalle-titulo">

                Resumen

            </div>

            <div class="resumen-grid">

                <div class="resumen-item">

                    <i class="fa-solid fa-dollar-sign"></i>

                    <h4>

                        $${parseFloat(cotizacion.costo_total).toLocaleString()}

                    </h4>

                    <span>

                        Costo Total

                    </span>

                </div>
                <div class="resumen-item">
                    <i class="fa-solid fa-clock"></i>
                    <h4>
                        ${formatearTiempo(cotizacion.tiempo_total_minutos)}
                    </h4>
                    <span>
                        Tiempo estimado
                    </span>
                </div>

                <div class="resumen-item">

                    <i class="fa-solid fa-layer-group"></i>

                    <h4>

                        ${cotizacion.detalles.length}

                    </h4>

                    <span>

                        Servicios

                    </span>

                </div>

            </div>

        </div>

        <div class="detalle-seccion">

            <div class="detalle-titulo">

                Servicios

            </div>

            ${cotizacion.detalles.map(servicio=>`

                <div class="servicio-card">

                    <h3>

                        ${servicio.servicio}

                    </h3>

                    <p>

                        ${servicio.descripcion ?? ""}

                    </p>

                    <div class="servicio-footer">

                        <span>

                            💰 $${parseFloat(servicio.costo).toLocaleString()}

                        </span>

                        <span>

                            ⏱ ${servicio.tiempo} ${servicio.unidad_tiempo}

                        </span>

                    </div>

                </div>

            `).join("")}

        </div>

        `;

        // ✅ GUARDAR EL ID EN EL BOTÓN PDF (AGREGAR ESTO)
        const btnPDF = document.getElementById('btnGenerarPDF');
        if (btnPDF) {
            btnPDF.dataset.idCotizacion = id;
        }

        // ✅ ACTUALIZAR BADGE DE ESTATUS (AGREGAR ESTO)
        const badge = document.getElementById('badgeEstatus');
        if (badge && cotizacion.estatus) {
            badge.textContent = cotizacion.estatus;
            badge.className = 'badge-estatus ' + cotizacion.estatus.toLowerCase();
        }

        abrirPanelDetalle();

    }catch(error){
        console.error(error);
        mostrarToast(
            "Error al cargar cliente",
            "error"
        );
    }
}

async function confirmarEliminarCliente(){

    try{

        const response = await fetch(

            `/Blackcore/Cotizador/public/delete-cliente?id=${idClienteEliminar}`,

            {

                method:"PUT"

            }

        );


        const data =
        await response.json();


        mostrarToast(
            data.mensaje,
            "success"
        );


        cerrarModalEliminar();

       cargarCotizaciones();


    }catch(error){

        console.error(error);

        mostrarToast(
            "Error al desactivar cliente",
            "error"
        );

    }

}

function cerrarModalEliminar(){

    idClienteEliminar = null;

    document
    .getElementById("modalEliminar")
    .classList.remove("show");

}

function editarCotizacion(id){

    window.location.href =
    `/Blackcore/Cotizador/public/Edit/EditarCotizacion?id=${id}`;

}

let idClienteEliminar = null;

function eliminarCotizacion(id){

    idClienteEliminar = id;

    document
    .getElementById("modalEliminar")
    .classList.add("show");

}