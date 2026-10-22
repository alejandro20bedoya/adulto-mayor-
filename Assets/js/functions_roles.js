var tableRoles;
var divLoading = document.querySelector("#divLoading");
document.addEventListener("DOMContentLoaded", function () {
  tableRoles = $("#tableRoles").dataTable({
    aProcessing: true,
    aServerSide: true,
    language: {
      url: "./es.json",
    },
    ajax: {
      url: base_url + "/Roles/getRoles",
      dataSrc: "",
    },
    columns: [
      { data: "idrol" },
      { data: "nombrerol" },
      { data: "descripcion" },
      { data: "status" },
      { data: "options" },
    ],
    resonsieve: "true",
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  // NUEVO ROL
  var formRol = document.querySelector("#formRol");
  formRol.onsubmit = function (e) {
    e.preventDefault();

    var intIdRol = document.querySelector("#idRol").value;
    var strNombre = document.querySelector("#txtNombre").value;
    var strDescripcion = document.querySelector("#txtDescripcion").value;
    var intStatus = document.querySelector("#listStatus").value;

    if (strNombre == "" || strDescripcion == "" || intStatus == "") {
      swal("Atención", "Todos los campos son obligatorios.", "error");
      return false;
    }

    divLoading.style.display = "flex";

    var request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    var ajaxUrl = base_url + "/Roles/setRol";
    var formData = new FormData(formRol);
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        var objData = JSON.parse(request.responseText);

        if (objData.status) {
          $("#modalFormRol").modal("hide");
          formRol.reset();

          swal({
            title: "Roles de usuario",
            text: objData.msg,
            icon: "success",
          }).then(() => {
            location.reload(); // ✅ recarga después del OK
          });
        } else {
          swal("Error", objData.msg, "error");
        }
      }

      divLoading.style.display = "none";
      return false;
    };
  };
});

function openModal() {
  document.querySelector("#idRol").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo Rol";
  document.querySelector("#formRol").reset();
  $("#modalFormRol").modal("show");
}

window.addEventListener(
  "load",
  function () {
    /*fntEditRol();
    fntDelRol();
    fntPermisos();*/
  },
  false
);

function fntEditRol(idrol) {
  document.querySelector("#titleModal").innerHTML = "Actualizar Rol";
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Actualizar";

  var idrol = idrol;
  var request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  var ajaxUrl = base_url + "/Roles/getRol/" + idrol;
  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#idRol").value = objData.data.idrol;
        document.querySelector("#txtNombre").value = objData.data.nombrerol;
        document.querySelector("#txtDescripcion").value =
          objData.data.descripcion;

        // ESTADO ACTIVO O INACTIVO
        // $('#listStatus').selectpicker('render');
        if (objData.data.status == 1) {
          document.querySelector("#listStatus").value = 1;
        } else {
          document.querySelector("#listStatus").value = 2;
        }

        $("#modalFormRol").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

function fntDelRol(idrol) {
  Swal.fire({
    title: "Eliminar Rol",
    text: "¿Realmente quiere eliminar el Rol?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "No, cancelar"
  }).then(function (result) {
    if (!result.isConfirmed) {
      return;
    }

    var request = new XMLHttpRequest();
    var ajaxUrl = base_url + "/Roles/delRol/";
    var formData = new URLSearchParams();
    formData.append("idrol", idrol);

    request.open("POST", ajaxUrl, true);
    request.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded"
    );

    request.onload = function () {
      if (request.status === 200) {
        try {
          var objData = JSON.parse(request.responseText);

          if (objData.status) {
            Swal.fire("¡Eliminado!", objData.msg, "success");
            tableRoles.api().ajax.reload(null, false);
          } else {
            Swal.fire("Atención", objData.msg, "error");
          }
        } catch (error) {
          console.error(request.responseText);
          Swal.fire("Error", "La respuesta del servidor no es válida.", "error");
        }
      } else {
        console.error("HTTP:", request.status, request.responseText);
        Swal.fire("Error", "No se pudo procesar la eliminación.", "error");
      }
    };

    request.onerror = function () {
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    };

    request.send(formData.toString());
  });
}

function fntPermisos(idrol) {
  var idrol = idrol;
  var request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  var ajaxUrl = base_url + "/Permisos/getPermisosRol/" + idrol;
  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      document.querySelector("#contentAjax").innerHTML = request.responseText;
      $(".modalPermiso").modal("show");
      document
        .querySelector("#modalFormPermiso")
        .addEventListener("submit", fntSavePermisos, false);
    }
  };
}

function fntSavePermisos(evnet) {
  evnet.preventDefault();
  var request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  var ajaxUrl = base_url + "/Permisos/setPermisos";
  var formElement = document.querySelector("#formPermiso");
  var formData = new FormData(formElement);
  request.open("POST", ajaxUrl, true);
  request.send(formData);

  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var objData = JSON.parse(request.responseText);
      if (objData.status) {
        // swal("Permisos de usuario", objData.msg ,"success");

        $("#modalFormPermiso").modal("hide");
        formPermiso.reset();
        swal("Permisos", objData.msg, "success");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}
