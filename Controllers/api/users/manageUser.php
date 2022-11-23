<?php

use Entities\UserEntity;
use Repositories\UserRepository;
use Utils\AuthUtils;
use Utils\TOTP;

require_once '../../../config.inc.php';
session_start();
header("Content-Type: application/json");
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$user = $_SESSION["user"];

function activateMFA(UserEntity $user): void
{
    if ($user->getMfaType() == 1)
        die(json_encode(["status" => "error", "message" => "Ya tienes activado el MFA"]));
    $user->setMfaData(TOTP::generatePrivateKey());
    $user->setMfaType(1);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "Escanea el código QR con tu aplicación de autenticación. Este código no se volverá a mostrar", "mfaUri" => "otpauth://totp/WTFAQ%20-%20(".$user->getUsername().")?secret=" . $user->getMfaData()]);
}

function activateEmailMFA(UserEntity $user): void
{
    if ($user->getMfaType() == 2)
        die(json_encode(["status" => "error", "message" => "Ya tienes activado el MFA"]));
    $user->setMfaType(2);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "MFA por correo activado correctamente"]);
}

function deactivateMFA(UserEntity $user): void
{
    $user->setMfaData(null);
    $user->setMfaType(0);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "MFA desactivado correctamente"]);
}

function changePassword(UserEntity $user, string $oldPassword, string $newPassword): void
{
    if (!password_verify($oldPassword, $user->getPassword()))
        die(json_encode(["status" => "error", "message" => "La contraseña actual no es correcta"]));
    $user->setPassword(UserEntity::hashPassword($newPassword));
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "Contraseña cambiada correctamente"]);
}

function changeEmail(UserEntity $user, string $newEmail): void
{
    $user->setEmail($newEmail);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "Correo cambiado correctamente"]);
}

function changeUsername(UserEntity $user, string $newUsername): void
{
    $user->setUsername($newUsername);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "Nombre de usuario cambiado correctamente"]);
}

function changeAvatar(UserEntity $user, string $newAvatar): void
{
    $user->setAvatar($newAvatar);
    UserRepository::updateUser($user);
    $_SESSION["user"] = $user;
    echo json_encode(["status" => "success", "message" => "Foto de perfil cambiada correctamente"]);
}
$method = $_POST["method"] ?? "get";
switch ($method) {
    case "activateMFA":
        activateMFA($user);
        break;
    case "activateEmailMFA":
        activateEmailMFA($user);
        break;
    case "deactivateMFA":
        deactivateMFA($user);
        break;
    case "changePassword":
        changePassword($user, $_POST["oldPassword"], $_POST["newPassword"]);
        break;
    case "changeEmail":
        changeEmail($user, $_POST["newEmail"]);
        break;
    case "changeUsername":
        changeUsername($user, $_POST["newUsername"]);
        break;
    case "changeAvatar":
        changeAvatar($user, $_POST["newAvatar"]);
        break;
    case "get":
        $userArray = $user->toArray();
        $userArray["email"] = $user->getEmail();
        $userArray["mfaType"] = $user->getMfaType();
        echo json_encode(["status" => "success", "data" => $userArray]);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Método no soportado"]);
        break;
}