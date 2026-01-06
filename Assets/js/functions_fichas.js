let tableFichas;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

// proceso que se vera en la tabla
document.addEventListener(
  "DOMContentLoaded",
  function () {
    //informacion de la tabla
    tableFichas = $("#tableFichas").DataTable({
      processing: true,
      serverSide: false, // <- desactivar server-side mientras debuggeas
      language: { url: "./es.json" },

      ajax: {
        url: base_url + "/Fichas/getFichas",
        dataSrc: "",
      },

      columns: [
        { data: "numeroficha" },
        { data: "nombreprograma" },
        { data: "nombres" },
        { data: "status_ficha" },
        { data: "options" },
      ],

      dom: "lBfrtip",

      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=fichas", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php?tipo=fichas", "_blank");
          },
        },
      ],
      responsive: true,
      destroy: true,
      pageLength: 10,
      order: [[0, "desc"]],
    });

    if (document.querySelector("#adultomy")) {
      let formFicha = document.querySelector("#adultomy");

      formFicha.onsubmit = function (e) {
        e.preventDefault();
        console.log("✅ Entró al submit");

        const inputVideo = document.querySelector("#video");

        // ===============================
        // VALIDACIÓN DEL VIDEO (4 minutos)
        // ===============================
        if (inputVideo && inputVideo.files.length > 0) {
          const file = inputVideo.files[0];

          // Validar que sea video
          if (!file.type.startsWith("video/")) {
            Swal.fire({
              icon: "error",
              title: "Archivo inválido",
              text: "El archivo seleccionado no es un video"
            });
            return;
          }

          const video = document.createElement("video");
          video.preload = "metadata";

          video.onloadedmetadata = function () {
            window.URL.revokeObjectURL(video.src);

            const duracion = video.duration; // segundos

            if (duracion > 240) {
              Swal.fire({
                icon: "warning",
                title: "Video muy largo",
                text: "El video no puede durar más de 4 minutos"
              });
              return;
            }

            // 👉 Si pasa la validación, enviamos el formulario
            enviarFormulario();
          };

          video.src = URL.createObjectURL(file);
        } else {
          // Si no hay video, enviamos normal
          enviarFormulario();
        }
      };

      // ===============================
      // FUNCIÓN DE ENVÍO DEL FORMULARIO
      // ===============================
      function enviarFormulario() {
        let formData = new FormData(formFicha);

        if (typeof divLoading !== "undefined") {
          divLoading.style.display = "flex";
        }

        let request = new XMLHttpRequest();
        request.open("POST", base_url + "/Fichas/setFicha", true);
        request.send(formData);

        request.onreadystatechange = function () {
          if (request.readyState === 4) {
            if (typeof divLoading !== "undefined") {
              divLoading.style.display = "none";
            }

            console.log("🔵 Status HTTP:", request.status);
            console.log("🔵 Respuesta cruda:", request.responseText);

            // ❌ Error de servidor
            if (request.status !== 200) {
              Swal.fire({
                icon: "error",
                title: "Error del servidor",
                text: "Código HTTP: " + request.status
              });
              return;
            }

            let objData;
            try {
              objData = JSON.parse(request.responseText);
            } catch (error) {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "La respuesta no es JSON válido"
              });
              return;
            }

            // ✅ Respuesta correcta
            if (objData.status === true) {
              formFicha.reset();
              Swal.fire({
                icon: "success",
                title: "Éxito",
                text: objData.msg
              });
            } else if (objData.status === "exist") {
              Swal.fire({
                icon: "warning",
                title: "Atención",
                text: objData.msg
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: objData.msg || "Error desconocido"
              });
            }
          }
        };
      }
    }
  },
  false
);

/// tabla para ver la infoamcion
function fntViewInfo(ideficha) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Fichas/getFicha/" + ideficha;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#celIdeFicha").innerHTML =
          objData.data.ideficha;
        document.querySelector("#celCodigoPrograma").innerHTML =
          objData.data.codigoprograma;
        document.querySelector("#celNumeroFicha").innerHTML =
          objData.data.numeroficha;
        document.querySelector("#celIdeInstructor").innerHTML =
          objData.data.nombres;
        document.querySelector("#celPrograma").innerHTML =
          objData.data.nombreprograma;

        $("#modalViewFicha").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

/// ver la ficha para atualizar
function fntEditInfo(element, ideficha) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Actualizar Ficha";
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Actualizar";

  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Fichas/getFicha/" + ideficha;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    let objData;
    if (request.readyState == 4 && request.status == 200) {
      objData = JSON.parse(request.responseText);
      console.log("lo que recibimos", objData);
      if (objData.status) {
        document.querySelector("#ideFicha").value = objData.data.ideficha;
        document.querySelector("#txtCodigoPrograma").value =
          objData.data.codigoprograma;
        document.querySelector("#txtNombrePrograma").value =
          objData.data.nombreprograma;
        document.querySelector("#txtFichaPrograma").value =
          objData.data.numeroficha;
        document.querySelector("#txtIdeInstructor").value =
          objData.data.identificacion;
        document.querySelector("#txtNombreInstructor").value =
          objData.data.nombres;
        document.querySelector("#listStatus").value = objData.data.status_ficha;
      }
    }
    $("#modalFormFicha").modal("show");
  };
}
/// para elimnar la ficha
function fntDelInfo(ideficha) {
  Swal.fire({
    title: "Eliminar Ficha",
    text: "¿Está seguro que desea eliminar la ficha?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    reverseButtons: true,
  }).then((result) => {
    if (!result.isConfirmed) return;

    divLoading.style.display = "flex";

    fetch(base_url + "/Fichas/delFicha", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "ideficha=" + ideficha,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success");

        // 🔹 Guardar la página actual
        let currentPage = tableFichas.page();

        // 🔹 Traer datos actualizados
        $.getJSON(base_url + "/Fichas/getFichas", function (data) {
          tableFichas.clear().rows.add(data).draw();
          tableFichas.page(currentPage).draw(false);
        });
      })
      .catch((err) => console.error("Error:", err))
      .finally(() => {
        divLoading.style.display = "none";
      });
  });
}

// modal
function openModal() {
  rowTable = "";
  document.querySelector("#ideFicha").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nueva Ficha";
  document.querySelector("#formFicha").reset();
  $("#modalFormFicha").modal("show");
}

// vista de informacion del programa
function fntViewInfoCodigoPrograma(codprograma) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Fichas/getPrograma/" + codprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombrePrograma").value =
          objData.data.nombreprograma;
      } else {
        document.getElementById("txtNombrePrograma").value = "";
      }
    }
  };
}

/// busqquedad del instructor nombre
function fntViewInfoIdeInstructor(identificacion) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Fichas/getInstructor/" + identificacion;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombreInstructor").value =
          objData.data.nombres;
        document.getElementById("txtUsuarioIde").value =
          objData.data.identificacion; // 🔹 agregar ID
      } else {
        document.getElementById("txtNombreInstructor").value = "";
        document.getElementById("txtUsuarioIde").value = "";
      }
    }
  };
}

