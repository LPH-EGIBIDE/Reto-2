<?php

use Entities\UserEntity;
use Exceptions\DataNotFoundException;
use Repositories\PostAnswerRepository;
use Utils\AuthUtils;

require_once "../../../config.inc.php";
session_start();

if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$user = $_SESSION['user'];

function getFavouriteAnswers (UserEntity $user): array {
    $favouriteAnswers = PostAnswerRepository::getUserFavouriteAnswers($user);
    $data = [];
    foreach ($favouriteAnswers as $favouriteAnswer) {
        $data[] = $favouriteAnswer->toArray();
    }
    return $data;
}

function addFavouriteAnswers (UserEntity $user, int $id): void {
    try {
        $answer = PostAnswerRepository::getPostAnswerById($id);
        if(!PostAnswerRepository::addUserFavouriteAnswer($user, $answer))
            throw new DataNotFoundException("Respuesta ya añadida a favoritos");
    }
    catch (DataNotFoundException $e) {
        throw new DataNotFoundException($e->getMessage());
    }
}

function removeFavouriteAnswers (UserEntity $user, int $id): void {
    try {
        $answer = PostAnswerRepository::getPostAnswerById($id);
        if(!PostAnswerRepository::removeUserFavouriteAnswer($user, $answer))
            throw new DataNotFoundException("Respuesta no encontrada en favoritos");
    }
    catch (DataNotFoundException $e) {
        throw new DataNotFoundException($e->getMessage());
    }
}

// Get method from _POST['method'] or default to 'get'
$method = $_POST['method'] ?? 'get';

try {
    switch ($method) {
        case 'get':
            echo json_encode(getFavouriteAnswers($user));
            break;
        case 'add':
            $id = $_POST['id'] ?? null;
            if ($id === null)
                die(json_encode(["status" => "error", "message" => "No se ha especificado el id de la respuesta"]));
            addFavouriteAnswers($user, $id);
            echo json_encode(["status" => "success", "message" => "Respuesta añadida a favoritos"]);
            break;
        case 'remove':
            $id = $_POST['id'] ?? null;
            if ($id === null)
                die(json_encode(["status" => "error", "message" => "No se ha especificado el id de la respuesta"]));
            removeFavouriteAnswers($user, $id);
            echo json_encode(["status" => "success", "message" => "Respuesta eliminada de favoritos"]);
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Método no soportado"]);
            break;
    }
} catch (DataNotFoundException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}