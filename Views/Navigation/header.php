<?php

use Utils\AuthUtils;

if (!AuthUtils::checkAuth()) {
    header("Location: /login");
    exit();
}
