<?php

use Exceptions\DataNotFoundException;
use Repositories\UserRepository;

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    //Check if the user is still on the database and reload the user object, if not, log out the user
    try {
        $user = UserRepository::getUserById($user->getId());
        $_SESSION["user"] = $user;
    } catch (DataNotFoundException $e) {
        unset($_SESSION["user"]);
        header("Location: /");
        exit();
    }
} else {
    header("Location: /login");
    exit;
}