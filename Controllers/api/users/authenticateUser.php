<?php
require_once "../../../config.inc.php";

use Entities\UserEntity;
use Exceptions\DataNotFoundException;
use Repositories\UserRepository;

session_start();
header('Content-Type: application/json');

if(isset($_SESSION["user"]) || isset($_SESSION["mfa_pending"])) {
    $user = $_SESSION["user"];
    if($user instanceof UserEntity){
        echo json_encode(["status" => "success", "user" => $user->getUsername(), "message" => "User is already logged in"]);
    }
} else {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    try {
        if (empty($username) || empty($password)) {
            throw new Exception("Los campos no pueden estar vacios". $username . $password);
        }
        $user = UserRepository::getUserByUsername($username);
        if($user->checkPassword($password)){
            if ($user->getMfaType() == 0) {
                $_SESSION["user"] = $user;
                echo json_encode(["status" => "success", "user" => $user->getUsername(), "message" => "Bienvenido de nuevo ".$user->getUsername()]);
            } else {
                $_SESSION["mfa_pending"] = $user;
                echo json_encode(["status" => "continueLogin", "user" => $user->getUsername()]);
            }


        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        }
    } catch (DataNotFoundException $e) {
        //Prevent user enumeration
        echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

}