$('.login-content [data-toggle="flip"]').click(function () {
	$('.login-box').toggleClass('flipped');
	return false;
});

var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector("#formLogin")) {
		let formLogin = document.querySelector("#formLogin");
		formLogin.onsubmit = function (e) {
			e.preventDefault();

			let strIdentificacion = document.querySelector('#txtIdentificacion').value;
			let strPassword = document.querySelector('#txtPassword').value;

			if (strIdentificacion == "" || strPassword == "") {
				// swal("Por favor", "Escribe la identificación y la contraseña.", "warning");
				swal({ title: "Por favor", text: "Escribe la identificación y la contraseña", imageUrl: "Assets/images/alerts.avif" });
				return false;
			} else {
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url + '/Login/loginUser';
				var formData = new FormData(formLogin);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {
					if (request.readyState !== 4) return;
					if (request.status >= 200 && request.status < 300) {
						try {
							var objData = JSON.parse(request.responseText);

							if (objData.status) {
								window.location.href = base_url + '/dashboard';
							} else {
								swal("Atención", objData.msg || "Credenciales incorrectas", "error");
								document.querySelector('#txtPassword').value = "";
							}
						} catch (error) {
							console.error("Error al interpretar el JSON:", error);
							swal("Error", "El servidor no devolvió una respuesta JSON válida.", "error");
						}
					} else {
						console.error("URL:", ajaxUrl);
						console.error("Estado HTTP:", request.status);
						console.error("Respuesta PHP:", request.responseText);

						swal(
							"Error HTTP " + request.status,
							"Revisa la consola (F12) para ver la respuesta del servidor.",
							"error"
						);
					}

					divLoading.style.display = "none";
				};
			}
		}
	}


}, false);