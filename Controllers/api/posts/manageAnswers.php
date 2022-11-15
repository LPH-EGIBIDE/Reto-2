<?php

require_once '../../../config.inc.php';
use Exceptions\DataNotFoundException;
use Repositories\PostAnswerRepository;
use Repositories\PostRepository;
use Utils\AuthUtils;

session_start();
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

if (!isset($_GET['id']) || !is_numeric($_GET['id']))
    die(json_encode(["status" => "error", "message" => "No se ha especificado el id del post"]));
$id = $_GET['id'];

try {
    $post = PostRepository::getPostById($id);
} catch (DataNotFoundException $e) {
    die(json_encode(["status" => "error", "message" => "El post no existe"]));
}

function getAnswers(int $id): array {
    try {
        $answerList = PostAnswerRepository::getPostAnswersByPost(PostRepository::getPostById($id));
        foreach ($answerList as $answer) {
            $answer->setAttachments(PostAnswerRepository::getAttachments($answer));
            $answers[] = $answer->toArray();
        }
    } catch (DataNotFoundException $e) {
        return [];
    }
    //order by upvotes desc
    usort($answers, function ($a, $b) {
        return $b['upvotes'] - $a['upvotes'];
    });
    return $answers;
}

// Get method from _GET['method'] or default to 'get'
$method = $_GET['method'] ?? 'get';

switch ($method) {
    case 'get':
        $data = $post->toArray();
        $data['answers'] = getAnswers($id);
        echo json_encode($data);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Método no soportado"]);
        break;
}