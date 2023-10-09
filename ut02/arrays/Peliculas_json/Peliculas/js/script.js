document.addEventListener("DOMContentLoaded", function () {
  var botonesEliminar = document.querySelectorAll(".eliminar-pelicula");

  botonesEliminar.forEach(function (boton) {
      boton.addEventListener("click", function (event) {
          event.preventDefault();

          var formId = boton.getAttribute("data-form-id");
          var formularioEliminar = document.getElementById(formId);

          var nombre = boton.getAttribute("data-nombre");

          Swal.fire({
              title: `¿Estás seguro de eliminar <br> ${nombre}?`,
              text: "Verifica antes de continuar",
              icon: "question",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Sí, eliminar",
          }).then((result) => {
              if (result.isConfirmed) {
                  formularioEliminar.submit();
              }
          });
      });
  });
});


// ! ESTRELLA

const stars = document.querySelectorAll(".star1");
const raitingInput = document.getElementById("raitingInput");

stars.forEach((star, index) => {
  star.addEventListener("click", () => {
    raitingInput.value = index + 1;

    for (let i = 0; i <= index; i++) {
      stars[i].classList.add("checked");
    }

    for (let i = index + 1; i < stars.length; i++) {
      stars[i].classList.remove("checked");
    }
  });

  star.addEventListener("dblclick", () => {
    raitingInput.value =0;

    for (let i = 0; i < stars.length; i++) {
      stars[i].classList.remove("checked");
    }
  });
}); 