let tableUsuarios;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
  "DOMContentLoaded",
  function () {
    tableUsuarios = $("#tableUsuarios").DataTable({
      aProcessing: true,
      aServerSide: true,
      language: {
        url: "./es.json",
      },
      ajax: {
        url: " " + base_url + "/Usuarios/getUsuarios",
        dataSrc: "",
      },

      columns: [
        { data: "ideusuario" },
        { data: "identificacion" }, /// sonas de cambio
        { data: "nombres" },
        { data: "apellidos" },
        { data: "celular" },
        { data: "correo" },
        { data: "nombrerol" },
        { data: "status" },
        { data: "options" },
      ],

      dom: "lBfrtip",
      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=usuarios", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php?tipo=usuarios", "_blank");
          },
        },
      ],

      resonsieve: "true",
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
    });

    if (document.querySelector("#formUsuario")) {
      let formUsuario = document.querySelector("#formUsuario");
      formUsuario.addEventListener("submit", function (e) {
        e.preventDefault();

        divLoading.style.display = "flex";

        let formData = new FormData(formUsuario);
        let request = new XMLHttpRequest();
        request.open("POST", base_url + "/Usuarios/setUsuario", true);

        request.onreadystatechange = function () {
          if (request.readyState === 4) {

            divLoading.style.display = "none";

            if (request.status === 200) {
              let objData = JSON.parse(request.responseText);

              if (objData.status) {

                // Detectar si fue EDITADO
                if (objData.msg === "editado") {
                  Swal.fire({
                    icon: "success",
                    title: "Actualizado",
                    text: "El usuario fue editado correctamente.",
                  });
                } else {
                  // Registrado nuevo
                  Swal.fire({
                    icon: "success",
                    title: "Registrado",
                    text: "El usuario fue registrado correctamente.",
                  });
                }

                // Recargar tabla y luego cerrar modal
                tableUsuarios.ajax.reload(function () {
                  $("#modalFormUsuario").modal("hide");
                  formUsuario.reset();
                  rowTable = "";
                }, false);

                // Si es edición actualizar la fila manualmente
                if (rowTable !== "") {
                  let intStatus = document.querySelector("#listStatus").value;
                  let htmlStatus =
                    intStatus == 1
                      ? '<span class="badge text-bg-success">Activo</span>'
                      : '<span class="badge text-bg-danger">Inactivo</span>';

                  rowTable.cells[1].textContent =
                    document.querySelector("#txtIdentificacionUsuario").value;

                  rowTable.cells[2].textContent =
                    document.querySelector("#txtRolUsuario").selectedOptions[0].text;

                  rowTable.cells[3].innerHTML = htmlStatus;
                }

              } else {
                Swal.fire({
                  icon: "warning",
                  title: "Atención",
                  text: objData.msg,
                });
              }

            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error en la petición",
              });
            }
          }
        };

        request.send(formData);
      });
    }

  },
  false
);

function fntViewInfo(ideusuario) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Usuarios/getUsuarioperfil/" + ideusuario;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        let estadoUsuario =
          objData.data.status == 1
            ? '<span class="badge text-bg-success">Activo</span>'
            : '<span class="badge text-bg-danger">Inactivo</span>';

        document.querySelector("#celIdeUsuario").innerHTML =
          objData.data.ideusuario;
        document.querySelector("#celIdentificacionUsuario").innerHTML =
          objData.data.identificacion; ///  berificacion de base de datos
        document.querySelector("#celNombresUsuario").innerHTML =
          objData.data.nombres;
        document.querySelector("#celApellidosUsuario").innerHTML =
          objData.data.apellidos;
        document.querySelector("#celCelularUsuario").innerHTML =
          objData.data.celular;
        document.querySelector("#celCorreoUsuario").innerHTML =
          objData.data.correo;
        document.querySelector("#celRolUsuario").innerHTML = objData.data.rolid;
        document.querySelector("#celEstadoUsuario").innerHTML = estadoUsuario;
        // document.querySelector("#celNombrePrograma").innerHTML = objData.data.nombreprograma;

        $("#modalViewUsuario").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

// eliminar
function fntDelInfo(ideusuario) {
  Swal.fire({
    title: "¿Eliminar Usuario?",
    text: "No podrás revertir esta acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6", // azul
    cancelButtonColor: "#d33", // rojo
    confirmButtonText: "Sí, eliminarlo",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(base_url + "/Usuarios/delUsuario", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "ideUsuario=" + ideusuario,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success");

        // 🔹 Guardar la página actual del DataTable
        let currentPage = tableUsuarios.page
          ? tableUsuarios.page()
          : tableUsuarios.api().page();

        // 🔹 Recargar datos sin perder la página
        $.getJSON(base_url + "/Usuarios/getUsuarios", function (data) {
          if (tableUsuarios.clear) {
            tableUsuarios.clear().rows.add(data).draw();
            tableUsuarios.page(currentPage).draw(false);
          } else {
            tableUsuarios.api().clear().rows.add(data).draw();
            tableUsuarios.api().page(currentPage).draw(false);
          }
        });
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire("Error", "No se pudo eliminar el usuario", "error");
      });
  });
}

function openModal() {
  rowTable = "";
  document.querySelector("#ideUsuario").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo Usuario";
  document.querySelector("#formUsuario").reset();

  fntRolesUsuario();  //  <-- ESTA LÍNEA ES OBLIGATORIA

  $("#modalFormUsuario").modal("show");
}

// =========================
// CARGAR ROLES EN EL SELECT
// =========================
function fntRolesUsuario(callback = null) {
  let selectRol = document.querySelector("#listRol");

  if (!selectRol) {
    console.warn("⚠ El select #listRol no existe en el DOM.");
    return;
  }

  // Mostrar loader
  selectRol.innerHTML = '<option value="">Cargando roles...</option>';

  let request = new XMLHttpRequest();
  let ajaxUrl = base_url + "/Usuarios/getSelectRoles";
  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      // Si el backend no envía datos, mostrar mensaje
      if (request.responseText.trim() === "") {
        selectRol.innerHTML = '<option value="">No hay roles disponibles</option>';
        return;
      }

      // Cargar roles correctamente
      selectRol.innerHTML =
        '<option value="">Seleccione un rol</option>' +
        request.responseText;

      // Ejecutar callback (importante para editar)
      if (callback) callback();
    }
  };
}

// =========================
// EDITAR USUARIO
// =========================

function fntEditInfo(element, ideusuario) {
  rowTable = element.parentNode.parentNode.parentNode;

  document.querySelector("#titleModal").innerHTML = "Actualizar Usuario";
  document.querySelector(".modal-header").classList.replace("headerRegister", "headerUpdate");
  document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Actualizar";

  // 1. Cargar los roles antes de consultar el usuario
  fntRolesUsuario(function () {
    let request = new XMLHttpRequest();
    let ajaxUrl = base_url + "/Usuarios/getUsuario/" + ideusuario;

    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
      if (request.readyState !== 4) return;

      if (request.status === 200) {
        try {
          let objData = JSON.parse(request.responseText);

          if (objData.status) {
            let usuario = objData.data;

            document.querySelector("#ideUsuario").value = usuario.ideusuario;
            document.querySelector("#txtIdentificacionUsuario").value = usuario.identificacion;
            document.querySelector("#txtNombresUsuario").value = usuario.nombres;
            document.querySelector("#txtApellidosUsuario").value = usuario.apellidos;
            document.querySelector("#txtCelularUsuario").value = usuario.celular;
            document.querySelector("#txtCorreoUsuario").value = usuario.correo;

            // 2. Seleccionar el rol mediante su ID
            const selectRol = document.querySelector("#listRol");
            const idRolUsuario = String(usuario.rolid);

            // Si el rol ya no está disponible, agregarlo temporalmente
            let opcionRol = Array.from(selectRol.options).find(
              opcion => opcion.value === idRolUsuario
            );

            if (!opcionRol && usuario.nombrerol) {
              opcionRol = new Option(
                usuario.nombrerol,
                idRolUsuario
              );
              selectRol.add(opcionRol);
            }

            selectRol.value = idRolUsuario;

            // 3. Seleccionar el estado
            document.querySelector("#listStatus").value =
              Number(usuario.status) === 1 ? "1" : "2";

            $("#modalFormUsuario").modal("show");
          } else {
            swal("Atención", objData.msg || "No se encontró el usuario.", "error");
          }
        } catch (error) {
          console.error("Error al procesar usuario:", error);
          console.error(request.responseText);
          swal("Error", "No se pudo interpretar la respuesta del servidor.", "error");
        }
      } else {
        console.error("Error HTTP:", request.status);
        swal("Error", "No se pudieron cargar los datos del usuario.", "error");
      }
    };
  });
}






