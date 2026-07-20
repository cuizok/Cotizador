console.log(document.getElementById("btnLogout"));

document
.getElementById("btnLogout")
.addEventListener("click", async ()=>{

    const response = await fetch(
        "/Blackcore/Cotizador/public/Logout",
        {
            method:"POST"
        }
    );

    const data = await response.json();

    mostrarToast(
        data.mensaje,
        "success"
    );

    setTimeout(()=>{

        window.location.href =
        "/Blackcore/Cotizador/public/login";

    },1000);

});