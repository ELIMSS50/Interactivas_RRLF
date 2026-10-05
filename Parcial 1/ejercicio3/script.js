// Botón para comprimir y expandir el sidebar
const btnMenu   = document.getElementById("btnMenu");
const sidebar   = document.getElementById("sidebar");
const principal = document.getElementById("principal");

btnMenu.addEventListener("click", function () {
  sidebar.classList.toggle("comprimido");
  principal.classList.toggle("comprimido"); // mueve el contenido junto al sidebar
});

// Mensaje al presionar el botón "Comprar"
const botones = document.querySelectorAll(".producto button");

botones.forEach(function (boton) {
  boton.addEventListener("click", function () {
    const nombre = boton.parentElement.querySelector("h3").textContent;
    boton.textContent = "¡Agregado!";

    setTimeout(function () {
      boton.textContent = "Comprar";
    }, 1500);

    console.log("Producto agregado: " + nombre);
  });
});
