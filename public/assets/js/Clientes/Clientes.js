document.addEventListener("DOMContentLoaded", () => {

    cargarClientes();

});


document
.getElementById("btnNuevo")
.addEventListener("click", () => {

    document.getElementById("formCliente").reset();

    document.getElementById("idCliente").value = "";

    document.getElementById("tituloModal").innerText =
    "Nuevo Cliente";

    abrirModal();

});



document
.getElementById("btnCancelar")
.addEventListener("click", cerrarModal);



document
.getElementById("btnCerrar")
.addEventListener("click", cerrarModal);



document
.getElementById("formCliente")
.addEventListener(
"submit",
function(e){

    e.preventDefault();


    const id =
    document.getElementById("idCliente").value;


    if(id === ""){

        crearCliente();

    }else{

        actualizarCliente();

    }

});



async function cargarClientes(){

    try{

        const response = await fetch(
            "/Blackcore/Cotizador/public/ClienteAll"
        );

        const clientes = await response.json();

        const tbody =
        document.getElementById("tbodyClientes");

        tbody.innerHTML = "";

        clientes.forEach(cliente=>{

            tbody.innerHTML += `

                <tr>

                    <td>${cliente.id}</td>

                    <td>${cliente.nombre}</td>

                    <td>${cliente.correo}</td>

                    <td>${cliente.telefono}</td>

                    <td>

                        <button
                            onclick="cargarClienteById(${cliente.id})">

                            <i class="fa-solid fa-pen"></i>

                        </button>

                        <button
                            onclick="eliminarCliente(${cliente.id})">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </td>

                </tr>

            `;

        });

    }catch(error){

        console.error(error);

        mostrarToast(
            "Error al cargar clientes",
            "error"
        );

    }

}



function abrirModal(){

    document
    .getElementById("modalCliente")
    .classList.add("show");

}


function cerrarModal(){

    document.getElementById("formCliente").reset();

    document.getElementById("idCliente").value = "";

    document
    .getElementById("modalCliente")
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

    cargarClientes();

}

async function cargarClienteById(id){


    try{


        const response = await fetch(
            `/Blackcore/Cotizador/public/clienteById?id=${id}`
        );


        const cliente = await response.json();



        document.getElementById("idCliente").value =
        cliente.id;

console.log(
    "ID CARGADO EN MODAL:",
    document.getElementById("idCliente").value
);

        document.getElementById("nombre").value =
        cliente.nombre;



        document.getElementById("correo").value =
        cliente.correo;



        document.getElementById("empresa").value =
        cliente.empresa;



        document.getElementById("telefono").value =
        cliente.telefono;



        document.getElementById("tituloModal").innerText =
        "Editar Cliente";



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



        cargarClientes();



    }catch(error){


        console.error(error);


        mostrarToast(
            "Error al actualizar cliente",
            "error"
        );


    }


}



function eliminarCliente(id){

    console.log(id);

}