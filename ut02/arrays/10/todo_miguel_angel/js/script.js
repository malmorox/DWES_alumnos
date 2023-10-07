// // eliminar.js

// document.addEventListener("DOMContentLoaded", function () {
//   var botonesEliminar = document.querySelectorAll(".eliminar-destino");

//   botonesEliminar.forEach((boton) => {
//     boton.addEventListener("click", () => {
//       var index = boton.getAttribute("data-index");
//       var nombre = boton.getAttribute("data-nombre");
//       confirmarEliminar(index, nombre);
//     });
//   });
// });

// function confirmarEliminar(index, nombre) {
//   Swal.fire({
//     title: `¿Estás seguro de eliminar el registro "${nombre}"?`,
//     text: "Verifica antes de continuar",
//     icon: "question",
//     showCancelButton: true,
//     confirmButtonColor: "#3085d6",
//     cancelButtonColor: "#d33",
//     confirmButtonText: "Sí, eliminar",
//   }).then((result) => {
//     if (result.isConfirmed) {
//     }
//   });
//   return false;
// }
document.addEventListener("DOMContentLoaded", function () {
  var botonesEliminar = document.querySelectorAll(".eliminar-destino");

  botonesEliminar.forEach(function (boton) {
    boton.addEventListener("click", function (event) {
      event.preventDefault(); // Evita la acción por defecto del botón

      // Obtén el formulario asociado al botón clicado
      var index = boton.getAttribute("data-index");
      var nombre = boton.getAttribute("data-nombre");
      var formularioEliminar = document.getElementById("form-eliminar-" + boton.dataset.index);

      Swal.fire({
        title: `¿Estás seguro de eliminar ${nombre}?`,
        text: "Verifica antes de continuar",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
      }).then((result) => {
        if (result.isConfirmed) {
          // Envía manualmente el formulario después de la confirmación
          formularioEliminar.submit();
        }
      });
    });
  });
});
