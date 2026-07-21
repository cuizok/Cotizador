<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Cotizaciones/Cotizacion.css">

<section class="panel">

    <div class="toolbar">

        <h2>Cotizaciones</h2>

        <div class="acciones">

            <input
                type="text"
                id="buscarCotizacion"
                placeholder="Buscar cotización..."
            >
            <a
                href="<?= BASE_URL ?>/NuevaCotizacion"
                id="btnNuevo">

                <i class="fa-solid fa-plus"></i>

                Nueva Cotización

            </a>

        </div>

    </div>

    <table class="tabla-cotizaciones">

        <thead>

            <tr>

                <th>ID</th>

                <th>Titulo</th>

                <th>Cliente</th>

                <th>Estatus</th>

                <th>Acciones</th>

            </tr>

        </thead>

        <tbody id="tbodyCotizacion">

        </tbody>

    </table>

</section>


<!-- MODAL -->

<div class="modal" id="modalCotizacion">

    <div class="modal-content">

        <div class="modal-header">

            <h2 id="tituloModal">

                Nueva cotización

            </h2>

            <button id="btnCerrar">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <form id="formCotizacion">

            <input
                type="hidden"
                id="idCotizacion"
            >

            <div class="input-group">

                <label>Titulo</label>

                <input
                    type="text"
                    id="titulo"
                >

            </div>

            <div class="input-group">

                <label>Descripcion</label>

                <input
                    type="text"
                    id="descripcion"
                >

            </div>


        
            <div class="modal-footer">

                <button
                    type="button"
                    id="btnCancelar"
                    class="btn-secondary"
                >

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn-primary"
                >

                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>

<div
    id="modalEliminar"
    class="modal">

    <div
        class="modal-content modal-confirmacion">

        <div class="modal-body">

            <i
                class="fa-solid fa-triangle-exclamation icon-warning">
            </i>

            <p>
                ¿Desea desactivar este cliente?
            </p>

            <small>

                El cliente dejará de estar disponible para nuevas
                operaciones, pero permanecerá almacenado para fines
                de historial y auditoría.

            </small>

        </div>

        <div class="modal-footer">

            <button
                id="btnCancelarEliminar"
                class="btn-secondary">

                Cancelar

            </button>

            <button
                id="btnConfirmarEliminar"
                class="btn-danger">

                Desactivar

            </button>

        </div>

    </div>

</div>

<script src="<?= BASE_URL ?>/assets/js/Cotizaciones/Cotizacion.js"></script>