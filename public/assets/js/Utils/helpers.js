function mostrarToast(mensaje, tipo = "success") {

    const container = document.getElementById("toast-container");

    const toast = document.createElement("div");

    toast.className = `toast ${tipo}`;

    toast.innerHTML = mensaje;

    container.appendChild(toast);

    setTimeout(() => {

        toast.classList.add("hide");

        setTimeout(() => {

            toast.remove();

        }, 400);

    }, 3000);

}