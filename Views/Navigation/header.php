<?php

use Utils\AuthUtils;

if (!AuthUtils::checkAuth()) {
    header("Location: /login");
    exit();
}

$user = $_SESSION['user'];
$title = empty($title) ? "WTFAQ" : $title;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/stylesheets/paginaPrin.css">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container" id="container">

<header class="cabecera">
    <img src="/assets/img/lph-black.png" class="altLogo" alt="Logo cabecera">
</header>

<nav class="navegador">
    <ul class="navI">
        <li><a href="#">Preguntas</a></li>
        <li><a href="#">Respuestas</a></li>
    </ul>
    <div class="logoMobile">
        <img src="/assets/img/lph-logo.png" class="altLogo"  alt="logo.png">
    </div>

    <ul class="navD">
        
        <li class="usuario">
            <div class="userProfile">
                <img src="/api/attachments/id/<?= $user->getAvatar()->getId() ?>" id="userLogo" alt="">
                <span id="username"><?= $user->getUsername() ?></span>
            </div>
                <ul class="opUsuario">
                    <li><a href="/user/profile">Mi perfil</a></li>
                    <li><a href="#">Pregunta favorita</a></li>
                    <li><a href="/logout">Cerrar sesion</a></li>
                </ul>
        </li>
        <li class="notificaciones">
            <div id="notifications"><i class="fa-solid fa-bell"></i> <span id="notifiText">Notificaciones</span></div>
            <div class="listaNoti hoverElement">
                <div class="contenidoNoti hoverElement">
                  <h3>Alertas</h3>
                  <hr>
                  <div class="notificacion hoverElement">
                    <i class="fa-regular fa-bell notifiIcon" ></i>
                    <p class="mensaje">Mensaje</p>
                    <div class="fecha">
                        <p>Hace: 1 dia</p>
                    </div>
                    <i class="fa-solid fa-trash basura" ></i>
                  </div>

                </div>
            </div>
        </li>
    </ul>
</nav>