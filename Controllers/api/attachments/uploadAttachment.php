<?php
require '../../../config.inc.php';

session_start();

if (!\Utils\AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));