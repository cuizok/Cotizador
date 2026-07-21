<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Cotizaciones/NuevaCotizacion.css"
>

<section class="panel">
    <div class="toolbar">
        <h2>Nueva Cotización</h2>
        <div class="acciones">
            <button id="btnVolver" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </button>
        </div>
    </div>

    <!-- Layout Principal -->
    <div class="cotizacion-layout">
        <!-- Columna Izquierda - Formulario -->
        <div class="col-formulario">
            <!-- Card 1: Información General -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-info-circle"></i> Información General</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="cliente">Cliente <span class="required">*</span></label>
                        <select id="cliente" class="form-control">
                            <option value="">Seleccione un cliente...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="titulo" 
                            class="form-control" 
                            placeholder="Ej: Sistema de Inventarios"
                        >
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea 
                            id="descripcion" 
                            class="form-control" 
                            rows="3" 
                            placeholder="Breve descripción del proyecto..."
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Card 2: Servicios -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-list-check"></i> Servicios</h3>
                    <span id="contadorServicios" class="badge badge-primary">0 servicios</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="tabla-servicios" id="tablaServicios">
                            <thead>
                                <tr>
                                    <th style="width:25%">Servicio</th>
                                    <th style="width:30%">Descripción</th>
                                    <th style="width:15%">Costo ($)</th>
                                    <th style="width:10%">Tiempo</th>
                                    <th style="width:15%">Unidad</th>
                                    <th style="width:5%"></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyServicios">
                                <!-- Las filas se agregarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    
                    <button id="btnAgregarServicio" class="btn-agregar">
                        <i class="fa-solid fa-plus"></i>
                        Agregar Servicio
                    </button>
                </div>
            </div>

            <!-- Card 3: Resumen -->
            <div class="card card-resumen">
                <div class="card-header">
                    <h3><i class="fa-solid fa-calculator"></i> Resumen</h3>
                </div>
                <div class="card-body">
                    <div class="resumen-grid">
                        <div class="resumen-item">
                            <span class="resumen-label">Total Servicios</span>
                            <span class="resumen-valor" id="totalServicios">0</span>
                        </div>
                        <div class="resumen-item">
                            <span class="resumen-label">Costo Total</span>
                            <span class="resumen-valor" id="costoTotal">$0.00</span>
                        </div>
                        <div class="resumen-item full-width">
                            <span class="resumen-label">Tiempo Estimado</span>
                            <span class="resumen-valor" id="tiempoTotal">0 días</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="btnCancelar" class="btn-secondary">
                        <i class="fa-solid fa-times"></i>
                        Cancelar
                    </button>
                    <button id="btnGuardar" class="btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Guardar Cotización
                    </button>
                </div>
            </div>
        </div>

        <!-- Columna Derecha - Panel Lateral -->
        <div class="col-lateral">
            <div class="card card-lateral">
                <div class="card-header">
                    <h3><i class="fa-solid fa-chart-simple"></i> Resumen Rápido</h3>
                </div>
                <div class="card-body">
                    <div class="lateral-info">
                        <div class="lateral-item">
                            <span class="lateral-icon">
                                <i class="fa-solid fa-users"></i>
                            </span>
                            <div>
                                <span class="lateral-label">Cliente</span>
                                <span class="lateral-valor" id="lateralCliente">No seleccionado</span>
                            </div>
                        </div>
                        <div class="lateral-item">
                            <span class="lateral-icon">
                                <i class="fa-solid fa-file-lines"></i>
                            </span>
                            <div>
                                <span class="lateral-label">Título</span>
                                <span class="lateral-valor" id="lateralTitulo">Sin título</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="lateral-totales">
                        <div class="total-item">
                            <span class="total-label">Servicios</span>
                            <span class="total-valor" id="lateralTotalServicios">0</span>
                        </div>
                        <div class="total-item">
                            <span class="total-label">Costo</span>
                            <span class="total-valor" id="lateralCostoTotal">$0.00</span>
                        </div>
                        <div class="total-item total-tiempo">
                            <span class="total-label">Tiempo</span>
                            <span class="total-valor" id="lateralTiempoTotal">0 días</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/Cotizaciones/NuevaCotizacion.js"></script>