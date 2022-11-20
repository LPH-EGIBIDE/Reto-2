<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/stylesheets/login.css">
    <title>WTFAQ - Iniciar Sesión</title>
</head>
<!-- Inline style para evitar que SWAL lo sobreescriba -->
<body style="height: 100vh!important;">

<div class="container" id="container">
    <div class="form-container sign-up-container">

        <form action="#" id="signupForm">
            <h1>Crear cuenta</h1>

            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" id="regBtn">Registrarse</button>
            <button class="ghost" id="signIn2">Conectar</button>
        </form>

    </div>

    <div class="form-container sign-in-container">

        <form action="#" id="loginForm">
            <h1>Inicio de sesión</h1>

            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <a href="#" onclick="resetPasswordEmailModal()" id="olvidar">Olvidaste la contraseña?</a>
            <button type="submit" id="loginBtn">Iniciar sesión</button>
            <button class="ghost" id="signUp2">Registrarse</button>
        </form>

    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Bienvenido de vuelta!</h1>
                <p>Para poder conectarte, ponga sus datos.</p>
                <button class="ghost" id="signIn">Conectar</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hola, amigo!</h1>
                <p>Si eres nuevo, registrate introduciendo tus datos.</p>
                <button class="ghost" id="signUp">Registrarse</button>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/login.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
</body>
</html>