<link 
rel="stylesheet" 
href="<?= BASE_URL ?>/assets/css/Cotizaciones/NuevaCotizacion.css"
>


<section class="cot-page">


<header class="cot-header">

<button id="btnVolver" class="btn-icon">

<i class="fa-solid fa-arrow-left"></i>

</button>


<div class="cot-header-title">

<h1>Editar Cotización</h1>

<p>Actualiza la propuesta existente</p>

</div>



<div class="cot-header-actions">


<button id="btnCancelar" class="btn-ghost">

<i class="fa-solid fa-xmark"></i>

Cancelar

</button>



<button id="btnActualizar" class="btn-solid">

<i class="fa-solid fa-floppy-disk"></i>

Actualizar cotización

</button>


</div>


</header>





<div class="stat-strip">


<div class="stat-card">

<span class="stat-icon stat-icon--indigo">

<i class="fa-solid fa-layer-group"></i>

</span>


<div class="stat-text">

<span class="stat-value" id="statServicios">
0
</span>


<span class="stat-label">
Servicios
</span>


</div>


</div>





<div class="stat-card">


<span class="stat-icon stat-icon--green">

<i class="fa-solid fa-sack-dollar"></i>

</span>


<div class="stat-text">

<span class="stat-value" id="statCosto">
$0
</span>


<span class="stat-label">
Costo total
</span>


</div>


</div>






<div class="stat-card">


<span class="stat-icon stat-icon--amber">

<i class="fa-solid fa-hourglass-half"></i>

</span>


<div class="stat-text">

<span class="stat-value" id="statTiempo">
0 días
</span>


<span class="stat-label">
Tiempo estimado
</span>


</div>


</div>





<div class="stat-card">


<span class="stat-icon stat-icon--slate">

<i class="fa-solid fa-circle-check"></i>

</span>


<div class="stat-text">

<span class="stat-value" id="statProgreso">
0/3
</span>


<span class="stat-label">
Listo para actualizar
</span>


</div>


</div>



</div>







<div class="cot-grid">



<div class="cot-main">



<div class="mini-card">


<div class="mini-card-head">

<span class="mini-card-num">
Paso 1
</span>


<h2>

<i class="fa-regular fa-address-card"></i>

Cliente y título

</h2>


</div>




<div class="mini-card-body">



<div class="field-row">


<div class="field">


<label>
Cliente
</label>


<select 
id="cliente"
class="field-control">

</select>


</div>



<div class="field">


<label>
Título
</label>


<input 
id="titulo"
class="field-control">


</div>


</div>




<div class="field">


<label>
Descripción
</label>


<textarea
id="descripcion"
class="field-control">
</textarea>


</div>


</div>


</div>








<div class="mini-card">


<div class="mini-card-head">


<span class="mini-card-num">
Paso 2
</span>



<h2>

<i class="fa-solid fa-list-check"></i>

Servicios

</h2>


<span 
class="chip-count"
id="contadorServicios">
0
</span>


</div>





<div class="mini-card-body">


<div 
id="tbodyServicios"
class="servicios-list">
</div>



<button 
id="btnAgregarServicio"
class="btn-add-service">

<i class="fa-solid fa-plus"></i>

Agregar servicio

</button>



</div>



</div>




</div>









<aside class="cot-side">


<div class="side-card">


<div class="side-card-head">

<i class="fa-regular fa-eye"></i>

Vista rápida

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

Servicios agregados

</li>


</ul>




<div class="side-preview">


<div class="preview-row">

<span>

<i class="fa-regular fa-user"></i>

Cliente

</span>


<strong id="lateralCliente">
-
</strong>


</div>




<div class="preview-row">


<span>

<i class="fa-regular fa-tag"></i>

Título

</span>


<strong id="lateralTitulo">
-
</strong>


</div>



</div>



<p class="side-hint">

Los cambios se guardarán sobre la cotización actual.

</p>



</div>


</aside>



</div>



</section>




<script>

const ID_COTIZACION = <?= $idCotizacion ?>;

</script>



<script src="<?= BASE_URL ?>/assets/js/Cotizaciones/EditarCotizacion.js"></script>