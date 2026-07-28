<!-- ============================================ -->
<!-- AJUSTES - CONFIGURACIÓN DE PDF               -->
<!-- ============================================ -->

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Ajustes/Ajustes.css">

<section class="ajustes-page">

    <!-- HEADER -->
    <header class="ajustes-header">
        <button id="btnVolver" class="btn-icon" title="Volver">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="ajustes-header-title">
            <h1>Configuración de Ajustes</h1>
            <p>Personaliza los mensajes para tus cotizaciones en PDF</p>
        </div>
        <div class="ajustes-header-actions">
            <button id="btnCancelar" class="btn-ghost">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </button>
            <button id="btnGuardar" class="btn-solid">
                <i class="fa-regular fa-floppy-disk"></i> Guardar cambios
            </button>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL (SIN CARDS) -->
    <div class="ajustes-content">

        <!-- FORMULARIO -->
        <div class="ajustes-form">
            
            <!-- Remitente -->
            <div class="field-group">
                <label for="remitente">Remitente <span class="required">*</span></label>
                <input type="text" id="remitente" class="field-control" placeholder="Ej: BlackCore Cotizaciones">
            </div>

            <!-- Mensaje de Presentación -->
            <div class="field-group">
                <label for="mensajePresentacion">Mensaje de Presentación <span class="required">*</span></label>
                <textarea id="mensajePresentacion" class="field-control" rows="3" placeholder="Mensaje de bienvenida o presentación..."></textarea>
            </div>

            <!-- Mensaje de Agradecimiento -->
            <div class="field-group">
                <label for="mensajeAgradecimiento">Mensaje de Agradecimiento <span class="required">*</span></label>
                <textarea id="mensajeAgradecimiento" class="field-control" rows="3" placeholder="Mensaje de agradecimiento..."></textarea>
            </div>

            <!-- Mensaje de Pie -->
            <div class="field-group">
                <label for="mensajePie">Mensaje de Pie de Página <span class="required">*</span></label>
                <textarea id="mensajePie" class="field-control" rows="2" placeholder="Ej: Este documento es una cotización sujeta a cambios..."></textarea>
            </div>

        </div>

        <!-- PANEL LATERAL - PREVIEW -->
        <aside class="ajustes-preview">
            <div class="preview-card">
                <div class="preview-card-head">
                    <i class="fa-regular fa-eye"></i> Vista previa en PDF
                </div>

                <div class="preview-body">
                    <div class="preview-item">
                        <span><i class="fa-regular fa-user"></i> Remitente</span>
                        <strong id="previewRemitente">-</strong>
                    </div>
                    <div class="preview-item">
                        <span><i class="fa-regular fa-message"></i> Presentación</span>
                        <strong id="previewPresentacion">-</strong>
                    </div>
                    <div class="preview-item">
                        <span><i class="fa-regular fa-handshake"></i> Agradecimiento</span>
                        <strong id="previewAgradecimiento">-</strong>
                    </div>
                    <div class="preview-item">
                        <span><i class="fa-regular fa-pen"></i> Pie de página</span>
                        <strong id="previewPie">-</strong>
                    </div>
                </div>

                <div class="preview-footer">
                    <i class="fa-regular fa-circle-info"></i>
                    Estos mensajes aparecerán en el PDF de cada cotización.
                </div>
            </div>
        </aside>

    </div>

</section>

<script src="<?= BASE_URL ?>/assets/js/Ajustes/Ajustes.js"></script>