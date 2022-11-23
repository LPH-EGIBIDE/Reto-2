<?php
require_once __DIR__.'/../config.inc.php';

use Repositories\UserRepository;
use Utils\AuthUtils;

session_start();

$importsCss = ["/assets/stylesheets/miPerfil.css"];
$importsJs = [];

if (!AuthUtils::checkAuth()) {
    header("Location: /login");
    exit();
}

$user = $_SESSION['user'];
$userId = intval($_GET['id']) ?? $user->getId() ?? null;

require APP_ROOT.'Views/Navigation/header.php';

try {
    $profileUser = $user->getId() == $userId ? $user : UserRepository::getUserById($userId);
    require APP_ROOT.'Views/miPerfil.php';
} catch (\Exceptions\DataNotFoundException $e) {
    require APP_ROOT.'Views/404.php';
}
require APP_ROOT.'Views/Navigation/footer.php';
