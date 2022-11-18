//Eventos del html
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const signUpButton2 = document.getElementById('signUp2');
const signInButton2 = document.getElementById('signIn2');
const container = document.getElementById('container');

//Funciones para html
signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});


signUpButton2.addEventListener('click', () => {
	container.classList.add("cambio");
	container.classList.remove("cambio2");
});

signInButton2.addEventListener('click', () => {
	container.classList.add("cambio2");
	container.classList.remove("cambio");
});


//Recogida de datos

document.getElementById("loginForm").addEventListener("submit", function(e){
	e.preventDefault();
	performLogin(e.target);
});

document.getElementById("signupForm").addEventListener("submit", function(e){
	e.preventDefault();
	performRegister(e.target);
});


function performLogin(formElement){
	//Recogemos los datos del formulario por el campo name
	let username = formElement.username.value;
	let password = formElement.password.value;
	//Creamos un objeto con los datos
	let data = {
		username: username,
		password: password
	}
	//Seteamos el elemento loginBtn a un spinner con fontawesome
	document.getElementById("loginBtn").innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

	//Creamos una petición POST a la API con contenido urlencoded
	fetch("/api/users/authenticateUser", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(data)
	})
	.then(response => response.json())
	.then(data => {
		switch (data.status) {
			case "success":
				showToast(data.message, "success", () => {
					window.location.href = "/";
				});
				break;
			case "continueLogin":
				handleMfa(data.message);
				break;
			case "error":
				showToast(data.message, "error", () => {});
				break;
			default:
				showToast("Error desconocido", "error", () => {});
				break;
		}
		//Seteamos el elemento loginBtn a Iniciar Sesión
		document.getElementById("loginBtn").innerHTML = 'Iniciar Sesión';
	});

}

function performRegister(formElement){
	//Recogemos los datos del formulario por el campo name
	let username = formElement.username.value;
	let password = formElement.password.value;
	let email = formElement.email.value;
	//Creamos un objeto con los datos
	let data = {
		username: username,
		password: password,
		email: email
	}
	//Seteamos el elemento loginBtn a un spinner con fontawesome
	document.getElementById("regBtn").innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

	//Creamos una petición POST a la API con contenido urlencoded
	fetch("/api/users/registerUser", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(data)
	})
		.then(response => response.json())
		.then(data => {
			switch (data.status) {
				case "success":
					showToast(data.message, "success", () => {
						window.location.href = "/login";
					});
					break;
				case "error":
					showToast(data.message, "error", () => {});
					break;
				default:
					showToast("Error desconocido", "error", () => {});
					break;
			}
			//Seteamos el elemento signUp2 a Registrarse
			document.getElementById("regBtn").innerHTML = 'Registrarse';
		});

}

function handleMfa(message){
	//Show a sweetalert with a form to enter the MFA code
	Swal.fire({
		title: "Verificación en dos pasos",
		text: message,
		input: "text",
		inputAttributes: {
			autocapitalize: "off"
		},
		showCancelButton: true,
		confirmButtonText: "Verificar",
		showLoaderOnConfirm: true,
		preConfirm: (code) => {
			return fetch("/api/users/twoFactorAuthentication", {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: new URLSearchParams({
					mfaCode: code
				})
			})
			.then(response => response.json())
			.then(data => {
				switch (data.status) {
					case "success":
						showToast(data.message, "success", () => {
							window.location.href = "/";
						});
						break;
					case "error":
						showToast(data.message, "error", () => {
							handleMfa();
						});

						break;
					default:
						showToast("Error desconocido", "error", () => {
							handleMfa();
						});
						handleMfa();
						break;
				}
			});
		},
		allowOutsideClick: () => !Swal.isLoading()
	});
}


function showToast(message, type, callback){
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 2000,
		timerProgressBar: true
	})
	Toast.fire({
		icon: type,
		title: message
	}).then(callback)
}