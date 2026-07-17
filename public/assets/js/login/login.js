function mostrarToast(mensaje, tipo = "success") {

    const container = document.getElementById(
        "toast-container"
    );

    const toast = document.createElement("div");

    toast.className = `toast ${tipo}`;

    toast.innerHTML = mensaje;

    container.appendChild(toast);


    setTimeout(()=>{

        toast.classList.add("hide");

        setTimeout(()=>{

            toast.remove();

        },400);


    },3000);

}



document
.getElementById('loginForm')
.addEventListener('submit', async (e) => {

    e.preventDefault();


    const correo = document
        .getElementById('correo')
        .value
        .trim();

    const password = document
        .getElementById('password')
        .value
        .trim();



    if (!correo || !password) {

        mostrarToast(
            "Completa todos los campos",
            "warning"
        );

        return;
    }



    try {


        const response = await fetch(
            `/Blackcore/Cotizador/public/login`,
            {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({

                    correo,
                    password

                })

            }
        );



        const data = await response.json();



        if (!response.ok) {


            mostrarToast(
                data.mensaje,
                "error"
            );


            return;

        }



        mostrarToast(
            data.mensaje,
            "success"
        );



        setTimeout(()=>{

            window.location.href = `/Blackcore/Cotizador/public/Home`;

        },1500);



    } catch(error) {


        console.error(error);


        mostrarToast(
            "Error de conexión con el servidor",
            "error"
        );


    }


});