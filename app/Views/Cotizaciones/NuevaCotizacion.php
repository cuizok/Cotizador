<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Cotizaciones/NuevaCotizacion.css"
>

<section class="panel">
    <div class="toolbar">
        <h2><i class="fa-regular fa-file-lines"></i> Nueva Cotización</h2>
        <div class="acciones">
            <button id="btnVolver" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </button>
        </div>
    </div>

    <div class="cotizacion-layout">
        <!-- Columna Principal -->
        <div class="col-formulario">
            <!-- Card Única -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-regular fa-pen-to-square"></i> Datos del Proyecto</h3>
                    <span id="contadorServicios" class="badge badge-primary">0</span>
                </div>
                <div class="card-body">
                    <!-- Fila Cliente + Título -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cliente">Cliente <span class="required">*</span></label>
                            <select id="cliente" class="form-control">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="titulo">Título <span class="required">*</span></label>
                            <input type="text" id="titulo" class="form-control" placeholder="Ej: Sistema RH">
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" rows="1" placeholder="Breve descripción..."></textarea>
                    </div>

                    <hr class="separador">

                    <!-- Servicios -->
                    <div>
                        <label style="font-weight: 500; font-size: 0.6rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.4px;">
                            <i class="fa-regular fa-list-check"></i> Servicios
                        </label>
                        <div class="table-responsive">
                            <table class="tabla-servicios">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Servicio</th>
                                        <th style="width:28%">Descripción</th>
                                        <th style="width:15%">Costo ($)</th>
                                        <th style="width:10%">Tiempo</th>
                                        <th style="width:17%">Unidad</th>
                                        <th style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyServicios">
                                    <!-- Filas dinámicas -->
                                </tbody>
                            </table>
                        </div>
                        <button id="btnAgregarServicio" class="btn-agregar">
                            <i class="fa-solid fa-plus"></i> Agregar Servicio
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Resumen -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-regular fa-chart-simple"></i> Resumen</h3>
                </div>
                <div class="card-body">
                    <div class="resumen-grid">
                        <div class="resumen-item">
                            <span class="resumen-label">Servicios</span>
                            <span class="resumen-valor" id="totalServicios">0</span>
                        </div>
                        <div class="resumen-item">
                            <span class="resumen-label">Costo Total</span>
                            <span class="resumen-valor" id="costoTotal">$0</span>
                        </div>
                        <div class="resumen-item">
                            <span class="resumen-label">Tiempo</span>
                            <span class="resumen-valor" id="tiempoTotal" style="font-size: 0.75rem;">0 días</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="btnCancelar" class="btn-secondary">
                        <i class="fa-solid fa-times"></i> Cancelar
                    </button>
                    <button id="btnGuardar" class="btn-primary">
                        <i class="fa-regular fa-floppy-disk"></i> Guardar
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-lateral">
            <div class="card card-lateral">
                <div class="card-header">
                    <h3><i class="fa-regular fa-circle-info"></i> Vista Rápida</h3>
                </div>
                <div class="card-body">
                    <div class="lateral-info">
                        <div class="lateral-item">
                            <span class="lateral-icon"><i class="fa-regular fa-user"></i></span>
                            <div style="flex:1;min-width:0;">
                                <span class="lateral-label">Cliente</span>
                                <span class="lateral-valor" id="lateralCliente">-</span>
                            </div>
                        </div>
                        <div class="lateral-item">
                            <span class="lateral-icon"><i class="fa-regular fa-tag"></i></span>
                            <div style="flex:1;min-width:0;">
                                <span class="lateral-label">Título</span>
                                <span class="lateral-valor" id="lateralTitulo">-</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="lateral-totales">
                        <div class="total-item">
                            <span class="total-label"><i class="fa-regular fa-circle-check"></i> Servicios</span>
                            <span class="total-valor" id="lateralTotalServicios">0</span>
                        </div>
                        <div class="total-item">
                            <span class="total-label"><i class="fa-regular fa-money-bill-1"></i> Costo</span>
                            <span class="total-valor" id="lateralCostoTotal">$0</span>
                        </div>
                        <div class="total-item total-tiempo">
                            <span class="total-label"><i class="fa-regular fa-clock"></i> Tiempo</span>
                            <span class="total-valor" id="lateralTiempoTotal">0 días</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/Cotizaciones/NuevaCotizacion.js"></script>