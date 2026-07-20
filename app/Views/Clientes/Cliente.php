<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Clientes/Clientes.css">

<section class="panel">

    <div class="toolbar">

        <h2>Clientes</h2>

        <div class="acciones">

            <input
                type="text"
                id="buscarCliente"
                placeholder="Buscar cliente..."
            >

            <button id="btnNuevo">

                <i class="fa-solid fa-plus"></i>

                Nuevo Cliente

            </button>

        </div>

    </div>

    <table class="tabla-clientes">

        <thead>

            <tr>

                <th>ID</th>

                <th>Nombre</th>

                <th>Correo</th>

                <th>Teléfono</th>

                <th>Acciones</th>

            </tr>

        </thead>

        <tbody id="tbodyClientes">

        </tbody>

    </table>

</section>


<!-- MODAL -->

<div class="modal" id="modalCliente">

    <div class="modal-content">

        <div class="modal-header">

            <h2 id="tituloModal">

                Nuevo Cliente

            </h2>

            <button id="btnCerrar">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <form id="formCliente">

            <input
                type="hidden"
                id="idCliente"
            >

            <div class="input-group">

                <label>Nombre</label>

                <input
                    type="text"
                    id="nombre"
                >

            </div>

            <div class="input-group">

                <label>Correo</label>

                <input
                    type="email"
                    id="correo"
                >

            </div>

              <div class="input-group">

                <label>Empresa</label>

                <input
                    type="text"
                    id="empresa"
                >

            </div>

            <div class="input-group">

                <label>Teléfono</label>

                <input
                    type="text"
                    id="telefono"
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

<script src="<?= BASE_URL ?>/assets/js/Clientes/Clientes.js"></script>