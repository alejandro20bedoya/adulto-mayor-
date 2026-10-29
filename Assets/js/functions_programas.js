let tableAdultoMayor;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
  "DOMContentLoaded",
  function () {
    formAdultomayor = $("#tableAdultoMayor").DataTable({
      aProcessing: true,
      aServerSide: true,
      language: {
        url: "./es.json",
      },
      ajax: {
        url: base_url + "/Programas/getAdultoMayor",
        dataSrc: "",
      },

      columns: [
        { data: "numerocedula" },
        { data: "nombre" },
        { data: "edad" },
        { data: "fecha" },
        { data: "telefono" },
        { data: "video_tipo" },
        { data: "status_adultomayor" },
        { data: "options" },
      ],

      dom: "lBfrtip",

      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=gestion", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php?tipo=gestion", "_blank");
          },
        },
      ],

      responsive: "true",
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
    });

    if (document.querySelector("#formAdultomayor")) {
      let formAdultomayor = document.querySelector("#formAdultomayor");

      formAdultomayor.onsubmit = function (e) {
        e.preventDefault();

        Swal.fire({
          title: "¿Estás seguro?",
          text: "¿Quieres editar este registro?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, editar",
          cancelButtonText: "Cancelar",
          reverseButtons: true,
        }).then((result) => {

          if (!result.isConfirmed) {
            return; // ❌ Cancelado
          }

          // 👉 Continua normalmente con el AJAX
          if (typeof divLoading !== "undefined")
            divLoading.style.display = "flex";

          let request = window.XMLHttpRequest
            ? new XMLHttpRequest()
            : new ActiveXObject("Microsoft.XMLHTTP");

          let ajaxUrl = base_url + "/Programas/setAdultomayor";
          let formData = new FormData(formAdultomayor);

          request.open("POST", ajaxUrl, true);
          request.send(formData);

          request.onreadystatechange = function () {
            if (request.readyState != 4) return;

            if (typeof divLoading !== "undefined")
              divLoading.style.display = "none";

            if (request.status == 200) {
              let objData = JSON.parse(request.responseText);

              if (objData.status) {

                $("#modalFormAdultomayor").modal("hide");
                formAdultomayor.reset();

                Swal.fire("Actualizado", objData.msg, "success");
                setTimeout(() => location.reload(), 800);

              } else {
                Swal.fire("Error", objData.msg, "error");
              }
            } else {
              Swal.fire("Error", "No se pudo conectar con el servidor", "error");
            }
          };

        });
      };
    }

  },
  false
);

function verVideo(id) {
  fntViewVideo(id);
}

function fntViewVideo(id) {

  const video = document.getElementById('videoTagPreview');

  // Limpiar el video anterior
  video.pause();
  video.removeAttribute('src');
  video.load();

  // URL directa al controlador PHP
  const videoUrl =
    base_url + "/Programas/getVideo/" + encodeURIComponent(id);

  // Asignar la URL directamente al reproductor
  video.src = videoUrl;

  // Abrir el modal
  $('#modalViewVideo').modal('show');

  // Iniciar la carga del video
  video.load();
}

// Detener el video al cerrar el modal
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('modalViewVideo');
    const video = document.getElementById('videoTagPreview');

    if (!modal || !video) {
        console.error('No se encontró el modal o el reproductor.');
        return;
    }

    // Ejecutar cuando el modal comienza a cerrarse
    modal.addEventListener('hide.bs.modal', function () {

        video.pause();
        video.currentTime = 0;

        // Eliminar la fuente para detener la descarga y reproducción
        video.removeAttribute('src');

        video.querySelectorAll('source').forEach(function (source) {
            source.removeAttribute('src');
        });

        video.load();
    });

});

// vista de informacion
function fntViewInfo(id) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Programas/getVistaAdultoMayor/" + id;
  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#celIdeAdulto").innerHTML =
          objData.data.id;
        document.querySelector("#celCedulaAdulto").innerHTML =
          objData.data.numerocedula;
        document.querySelector("#celNombreAdulto").innerHTML =
          objData.data.nombre;
        document.querySelector("#celEdadAdulto").innerHTML =
          objData.data.edad;
        document.querySelector("#celFecha").innerHTML =
          objData.data.fecha;
        document.querySelector("#celTelefono").innerHTML =
          objData.data.telefono;
        document.querySelector("#celVideo").innerHTML =
          objData.data.video_tipo;
        document.querySelector("#celVereda").innerHTML =
          objData.data.vereda;
        document.querySelector("#celZona").innerHTML =
          objData.data.zona;
        document.querySelector("#celApoderado").innerHTML =
          objData.data.apoderado;
        document.querySelector("#celCedulaApoderado").innerHTML =
          objData.data.cedulaapoderado;
        document.querySelector("#celFechapago").innerHTML =
          objData.data.fecha_pago;
        // Mostrar el estado de verificación
        let estado = objData.data.estado;

        if (estado == 1) {
          document.querySelector("#celEstado").innerHTML =
            '<span class="badge bg-success">Verificado</span>';
        } else if (estado == 0) {
          document.querySelector("#celEstado").innerHTML =
            '<span class="badge bg-danger">No verificado</span>';
        } else {
          document.querySelector("#celEstado").innerHTML =
            '<span class="badge bg-secondary">Sin estado</span>';
        }
        document.querySelector("#celCiclo").innerHTML =
          objData.data.ciclo;
        document.querySelector("#celDescripcion").innerHTML =
          objData.data.descripcion;
        $("#modalViewPrograma").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

// Función para editar
function fntEditInfo(element, ideprograma) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Actualizar informacion del adulto mayor";
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
  let ajaxUrl = base_url + "/Programas/getVistaAdultoMayorEditar/" + ideprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#adultoid").value = objData.data.id;

        document.querySelector("#txtNumeroCedula").value =
          objData.data.numerocedula;

        // document.querySelector("#txtNivelPrograma").value =
        //   objData.data.nivelprograma;

        document.querySelector("#txtNombreAdulto").value =
          objData.data.nombre;

        document.querySelector("#txtFecha").value =
          objData.data.fecha;

        document.querySelector("#txtEdadAdulto").value =
          objData.data.edad;

        document.querySelector("#txttelefono").value =
          objData.data.telefono;

        document.querySelector("#txtVereda").value =
          objData.data.vereda;

        document.querySelector("#zona").value =
          objData.data.zona;

        document.querySelector("#txtNombreApoderado").value =
          objData.data.apoderado;

        document.querySelector("#txtNumeroCedulaApoderado").value =
          objData.data.cedulaapoderado;

        document.querySelector("#txtFechapago").value =
          objData.data.fecha_pago;

        document.querySelector("#txtEstado").value =
          objData.data.estado;

        document.querySelector("#txtNumeroCiclos").value =
          objData.data.ciclo;

        document.querySelector("#txtDescripcion").value =
          objData.data.descripcion;
      }
    }
    $("#modalFormAdultomayor").modal("show");
  };
}

// ELIMINAR
function fntDelInfo(idAdulto) {
  Swal.fire({
    title: "¿Eliminar registro?",
    text: "No podrás revertir esta acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6", // azul
    cancelButtonColor: "#d33", // rojo
    confirmButtonText: "Sí, eliminarlo",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(base_url + "/Programas/getEliminarAdulto", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "idadulto=" + idAdulto,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success");

        // 🔹 Guardar la página actual del DataTable
        let currentPage = formAdultomayor.page
          ? formAdultomayor.page()
          : formAdultomayor.api().page();

        // 🔹 Recargar datos sin perder la página
        $.getJSON(base_url + "/Programas/getAdultoMayor", function (data) {
          if (formAdultomayor.clear) {
            formAdultomayor.clear().rows.add(data).draw();
            formAdultomayor.page(currentPage).draw(false);
          } else {
            formAdultomayor.api().clear().rows.add(data).draw();
            formAdultomayor.api().page(currentPage).draw(false);
          }
        });
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire("Error", "No se pudo eliminar el programa", "error");
      });
  });
}

// para todos los modal de ver, editar y eliminar
function openModal() {
  rowTable = "";
  document.querySelector("#idePrograma").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo Programa";
  document.querySelector("#formAdultomayor").reset();
  $("#modalFormAdultomayor").modal("show");
}


// Delegación de eventos para reparar los onClick destruidos por DataTables
$(document).on("click", "button", function (e) {

  // VER VIDEO
  if (this.getAttribute("onClick")?.includes("verVideo")) {
    let id = this.getAttribute("onClick").match(/\d+/)[0];
    verVideo(id);
  }

  // VER INFORMACIÓN
  if (this.getAttribute("onClick")?.includes("fntViewInfo")) {
    let id = this.getAttribute("onClick").match(/\d+/)[0];
    fntViewInfo(id);
  }

  // EDITAR
  if (this.getAttribute("onClick")?.includes("fntEditInfo")) {
    let parts = this.getAttribute("onClick").match(/fntEditInfo\(this,(\d+)\)/);
    if (parts) {
      let id = parts[1];
      fntEditInfo(this, id);
    }
  }

  // ELIMINAR
  if (this.getAttribute("onClick")?.includes("fntDelInfo")) {
    let id = this.getAttribute("onClick").match(/\d+/)[0];
    fntDelInfo(id);
  }

});

