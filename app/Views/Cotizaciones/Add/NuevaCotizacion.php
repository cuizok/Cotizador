<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Cotizaciones/NuevaCotizacion.css"
>

<section class="cot-page">

    <!-- Header -->
    <header class="cot-header">
        <button id="btnVolver" class="btn-icon" title="Volver">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="cot-header-title">
            <h1>Nueva Cotización</h1>
            <p>Arma tu propuesta paso a paso</p>
        </div>
        <div class="cot-header-actions">
            <button id="btnCancelar" class="btn-ghost">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </button>
            <button id="btnGuardar" class="btn-solid">
                <i class="fa-regular fa-floppy-disk"></i> Guardar cotización
            </button>
        </div>
    </header>

    <!-- Franja de estadísticas -->
    <div class="stat-strip">
        <div class="stat-card" id="statCardServicios">
            <span class="stat-icon stat-icon--indigo"><i class="fa-solid fa-layer-group"></i></span>
            <div class="stat-text">
                <span class="stat-value" id="statServicios">0</span>
                <span class="stat-label">Servicios</span>
            </div>
        </div>
        <div class="stat-card" id="statCardCosto">
            <span class="stat-icon stat-icon--green"><i class="fa-solid fa-sack-dollar"></i></span>
            <div class="stat-text">
                <span class="stat-value" id="statCosto">$0</span>
                <span class="stat-label">Costo total</span>
            </div>
        </div>
        <div class="stat-card" id="statCardTiempo">
            <span class="stat-icon stat-icon--amber"><i class="fa-solid fa-hourglass-half"></i></span>
            <div class="stat-text">
                <span class="stat-value" id="statTiempo">0 días</span>
                <span class="stat-label">Tiempo estimado</span>
            </div>
        </div>
        <div class="stat-card" id="statCardProgreso">
            <span class="stat-icon stat-icon--slate"><i class="fa-solid fa-circle-check"></i></span>
            <div class="stat-text">
                <span class="stat-value" id="statProgreso">0/3</span>
                <span class="stat-label">Listo para guardar</span>
            </div>
        </div>
    </div>

    <div class="cot-grid">
        <!-- Columna principal -->
        <div class="cot-main">

            <!-- Mini-card: Cliente y título -->
            <div class="mini-card">
                <div class="mini-card-head">
                    <span class="mini-card-num">Paso 1</span>
                    <h2><i class="fa-regular fa-address-card"></i> Cliente y título</h2>
                </div>
                <div class="mini-card-body">
                    <div class="field-row">
                        <div class="field">
                            <label for="cliente">Cliente <span class="required">*</span></label>
                            <select id="cliente" class="field-control">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="titulo">Título <span class="required">*</span></label>
                            <input type="text" id="titulo" class="field-control" placeholder="Ej: Sistema RH">
                        </div>
                    </div>
                    <div class="field">
                        <label for="descripcion">Descripción <span class="opt">(opcional)</span></label>
                        <textarea id="descripcion" class="field-control" rows="1" placeholder="Breve descripción del proyecto..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Mini-card: Servicios -->
            <div class="mini-card">
                <div class="mini-card-head">
                    <span class="mini-card-num">Paso 2</span>
                    <h2><i class="fa-solid fa-list-check"></i> Servicios</h2>
                    <span class="chip-count" id="contadorServicios">0</span>
                </div>
                <div class="mini-card-body">
                    <div class="servicios-list" id="tbodyServicios">
                        <!-- Cards dinámicas de servicio -->
                    </div>
                    <button id="btnAgregarServicio" class="btn-add-service">
                        <i class="fa-solid fa-plus"></i> Agregar servicio
                    </button>

                    <!-- Mini resumen pegado a los servicios, para no tener que subir la pantalla -->
                    <div class="resumen-inline">
                        <div class="resumen-inline-item">
                            <i class="fa-solid fa-layer-group"></i>
                            <span id="resumenInlineServicios">0</span> servicios
                        </div>
                        <div class="resumen-inline-item">
                            <i class="fa-solid fa-sack-dollar"></i>
                            <span id="resumenInlineCosto">$0.00</span>
                        </div>
                        <div class="resumen-inline-item">
                            <i class="fa-solid fa-hourglass-half"></i>
                            <span id="resumenInlineTiempo">0 días</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Panel lateral -->
        <aside class="cot-side">
            <div class="side-card">
                <div class="side-card-head">
                    <i class="fa-regular fa-eye"></i> Vista rápida
                </div>

                <ul class="checklist">
                    <li id="checkCliente" class="check-item">
                        <i class="fa-regular fa-circle check-off"></i>
                        <i class="fa-solid fa-circle-check check-on"></i>
                        Cliente seleccionado
                    </li>
                    <li id="checkTitulo" class="check-item">
                        <i class="fa-regular fa-circle check-off"></i>
                        <i class="fa-solid fa-circle-check check-on"></i>
                        Título definido
                    </li>
                    <li id="checkServicios" class="check-item">
                        <i class="fa-regular fa-circle check-off"></i>
                        <i class="fa-solid fa-circle-check check-on"></i>
                        Al menos un servicio
                    </li>
                </ul>

                <div class="side-preview">
                    <div class="preview-row">
                        <span><i class="fa-regular fa-user"></i> Cliente</span>
                        <strong id="lateralCliente">-</strong>
                    </div>
                    <div class="preview-row">
                        <span><i class="fa-regular fa-tag"></i> Título</span>
                        <strong id="lateralTitulo">-</strong>
                    </div>
                </div>

                <p class="side-hint">Los totales se calculan solos, arriba en la franja de estadísticas.</p>
            </div>
        </aside>
    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/Cotizaciones/NuevaCotizacion.js"></script>