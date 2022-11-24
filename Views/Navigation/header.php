<?php
if (!isset($importsCss)){
    $importsCss = [];
}

$title = empty($title) ? "WTFAQ" : $title;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/stylesheets/main.css">
    <?php
    foreach ($importsCss as $import){
        echo "<link rel='stylesheet' href='$import'>";
    }
    ?>
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container" id="container">

<header class="cabecera">
    <img src="/assets/img/lph-black.png" class="mainLogo" alt="Logo cabecera">
</header>

<nav class="navegador">
    <ul class="navI listas">
        <li><a class="navLink" href="/searchPosts">Preguntas</a></li>
        <li><a class="navLink" href="#">Tutoriales</a></li>
    </ul>
    <div class="logoMobile">
        <img src="/assets/img/lph-logo.png" class="altLogo"  alt="logo.png">
    </div>

    <ul class="navD listas">
        
        <li class="usuario">
            <div class="userProfile">
                <img src="/api/attachments/id/<?= $user->getAvatar()->getId() ?>" id="userLogo" alt="">
                <span id="username"><?= $user->getUsername() ?></span>
            </div>
                <ul class="opUsuario listas">
                    <li><a class="navLink" href="/user/me">Mi perfil</a></li>
                    <li><a class="navLink" href="#">Ajustes</a></li>
                    <li><a class="navLink" href="/logout">Cerrar sesion</a></li>
                </ul>
        </li>
        <li class="notificaciones">
            <div id="notifications"><i class="fa-solid fa-bell"></i> <span id="notifiText">Notificaciones</span></div>
            <div class="listaNoti hoverElement">
                <div class="contenidoNoti hoverElement">
                  <h3>Alertas</h3>
                  <hr>
                    <div class="hoverelement" id="notificationContainer">
                        <div class="notificacion hoverElement">
                            <i class="fa-regular fa-bell notifiIcon" ></i>
                            <p class="mensaje">Mensaje</p>
                            <div class="fecha">
                                <p>Hace: <span class="fechaNotificacion">1 dia</span></p>
                            </div>
                            <i class="fa-solid fa-trash basura" ></i>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>