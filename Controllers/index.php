<?php
require_once __DIR__.'/../config.inc.php';
session_start();

use Utils\AuthUtils;

if (!AuthUtils::checkAuth()) {
    header("Location: /login");
    exit();
}
$user = $_SESSION['user'];


require APP_ROOT.'Views/Navigation/header.php';
require APP_ROOT.'Views/Navigation/footer.php';