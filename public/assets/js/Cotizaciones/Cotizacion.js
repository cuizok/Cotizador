document.addEventListener("DOMContentLoaded", () => {

   cargarCotizaciones();

});


document
.getElementById("btnNuevo")
.addEventListener("click", () => {

    document.getElementById("formCotizacion").reset();

    document.getElementById("idCotizacion").value = "";

    document.getElementById("tituloModal").innerText =
    "Nueva cotización";

    abrirModal();

});



document
.getElementById("btnCancelar")
.addEventListener("click", cerrarModal);



document
.getElementById("btnCerrar")
.addEventListener("click", cerrarModal);

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



document
.getElementById("formCotizacion")
.addEventListener(
"submit",
function(e){

    e.preventDefault();


    const id =
    document.getElementById("idCotizacion").value;


    if(id === ""){

        crearCliente();

    }else{

        actualizarCliente();

    }

});



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

                            <i class="fa-solid fa-pen"></i>

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



function abrirModal(){

    document
    .getElementById("modalCotizacion")
    .classList.add("show");

}


function cerrarModal(){

    document.getElementById("formCotizacion").reset();

    document.getElementById("idCotizacion").value = "";

    document
    .getElementById("modalCotizacion")
    .classList.remove("show");

}

async function crearCliente(){

    const cliente = {

        nombre:
        document.getElementById("nombre").value,

        correo:
        document.getElementById("correo").value,

        empresa:
        document.getElementById("empresa").value,

        telefono:
        document.getElementById("telefono").value

    };


    const response = await fetch(
        "/Blackcore/Cotizador/public/Insert-cliente",
        {

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:
            JSON.stringify(cliente)

        }
    );


    const data = await response.json();


    mostrarToast(
        data.mensaje,
        "success"
    );


    cerrarModal();

   cargarCotizaciones();

}

async function cargarCotizacionbyId(id){


    try{


        const response = await fetch(
            `/Blackcore/Cotizador/public/CotizacionById?id=${id}`
        );


        const cotizacion = await response.json();



        document.getElementById("idCotizacion").value =
        cotizacion.id;

        console.log(
            "ID CARGADO EN MODAL:",
            document.getElementById("idCotizacion").value
        );

        document.getElementById("titulo").value =
        cotizacion.titulo;



        document.getElementById("descripcion").value =
        cotizacion.descripcion;





        document.getElementById("tituloModal").innerText =
        "Editar Cotización";



        abrirModal();



    }catch(error){


        console.error(error);


        mostrarToast(
            "Error al cargar cliente",
            "error"
        );


    }


}

async function actualizarCliente(){


    const id =
    document.getElementById("idCliente").value;



    const cliente = {


        id:id,


        nombre:
        document.getElementById("nombre").value,


        correo:
        document.getElementById("correo").value,


        empresa:
        document.getElementById("empresa").value,


        telefono:
        document.getElementById("telefono").value


    };



    try{

        console.log(cliente);
        const response = await fetch(
            `/Blackcore/Cotizador/public/Update-cliente?id=${id}`,
            {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(cliente)
            }
        );



        const data =
        await response.json();
        console.log(data);



        mostrarToast(
            data.mensaje,
            "success"
        );



        cerrarModal();



       cargarCotizaciones();



    }catch(error){


        console.error(error);


        mostrarToast(
            "Error al actualizar cliente",
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


let idClienteEliminar = null;

function eliminarCotizacion(id){

    idClienteEliminar = id;

    document
    .getElementById("modalEliminar")
    .classList.add("show");

}