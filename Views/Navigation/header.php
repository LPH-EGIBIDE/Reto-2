<?php

use Utils\AuthUtils;

if (!AuthUtils::checkAuth()) {
    header("Location: /login");
    exit();
}

$user = $_SESSION['user'];

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/stylesheets/paginaPrin.css">
</head>
<body>

<div class="container" id="container">

<header class="cabecera">
    <img src="/assets/img/lph-black.png" alt="logo.png">
    <h4>El saber es poder</h4>
</header>

<nav class="navegador">
    <ul class="navI">
        <li><a href="#">Preguntas</a></li>
        <li><a href="#">Respuestas</a></li>
    </ul>
    <img src="/assets/img/lph-logo.png" alt="logo.png"> 
    <ul class="navD">
        
        <li class="usuario"><?= $user->getUsername() ?>
                <ul class="opUsuario">
                    <li><a href="/user/profile">Mi perfil</a></li>
                    <li><a href="#">Pregunta favorita</a></li>
                    <li><a href="/logout">Cerrar sesion</a></li>
                </ul>
        </li>
        <li class="notificaciones">Notificaciones
            <div class="listaNoti">
                <div class="contenidoNoti">
                  <h5>Alertas</h5>
                  <hr>
                  <div class="notificacion">
                    <i class="fa-regular fa-bell" id="notifiIcon"></i>
                    <p class="mensaje">Mensaje</p>
                    <div class="fecha">
                     <p>Hace: <p class="tiempo"> 1 dia</p></p>
                    </div>
                    <i class="fa-solid fa-trash" id="basura"></i>
                  </div>

                </div>
            </div>
        </li>
    </ul>
</nav>