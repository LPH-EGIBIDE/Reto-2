<?php
$user = $_SESSION["user"] ?? "";
if (DEBUG_MODE){
    $version = getBuildInfo()["buildName"] ." - ".$user->getUsername() ?? 'WTFAQ v0.0.0';
} else {
    $version = "&#169 Los Pollos Hermanos";
}




?>

<footer class="pie">

    <ul class="fotoI">
        <li><img src="/assets/img/lph-logo.png" ></li>
        <li class="version"><p> <?= $version ?>  </p></li>
    </ul>

    <ul class="listaD">
        <li><a href="#">Pagina web</a></li>
        <li><a href="#">Redes sociales</a></li>
        <li><a href="#">Contacto</a></li>
    </ul>

</footer>

</div>

</body>
<script src="//cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
</html>