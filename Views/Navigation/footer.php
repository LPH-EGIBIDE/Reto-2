<?php
$version = getBuildInfo()["buildName"] ?? 'WTFAQ v0.0.0';
$user = $_SESSION["user"] ?? "";

?>

<footer>
    <p> <?= $version ?> - <?= $user->getUsername() ?></p>
</footer>
