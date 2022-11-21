<?php

require_once '../../../config.inc.php';

use Entities\PostAnswerEntity;
use Entities\PostEntity;
use Entities\UserEntity;
use Exceptions\DataNotFoundException;
use Exceptions\PostException;
use Repositories\AttachmentRepository;
use Repositories\PostAnswerRepository;
use Repositories\PostRepository;
use Utils\AuthUtils;

session_start();
header('Content-Type: application/json');

if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if ($id == null || !is_numeric($id))
    die(json_encode(["status" => "error", "message" => "No se ha especificado el id del post"]));



try {



    // Get the action type
    $method = $_POST['action'] ?? 'get';

    switch ($method) {
        case 'get':
            $post = PostRepository::getPostById($id);
            $data = $post->toArray();
            $data['answers'] = getAnswers($post);
            // Add a view to the post
            $post->setViews($post->getViews() + 1);
            $data['views'] = $post->getViews();
            PostRepository::updatePost($post);
            // Return the data
            echo json_encode($data);
            break;
        case 'insert':
            $post = PostRepository::getPostById($id);
            $content = $_POST['content'] ?? '';
            $user = $_SESSION['user'];
            try {
                $answer = insertAnswer($content, $post, $user);
                echo json_encode(["status" => "success", "message" => "Respuesta insertada correctamente", "answerId" => $answer->getId()]);
            } catch (PostException $e) {
                if (DEBUG_MODE){
                    echo json_encode(["status" => "error", "message" => $e->getMessage(), "line" => $e->getLine(), "file" => $e->getFile()]);
                } else{
                    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                }
            }
            break;
        case 'delete':
            $post = PostAnswerRepository::getPostAnswerById($id);
            try {
                deleteAnswer($post);
                echo json_encode(["status" => "success", "message" => "Respuesta eliminada correctamente"]);
            } catch (PostException $e) {
                if (DEBUG_MODE){
                    echo json_encode(["status" => "error", "message" => $e->getMessage(), "line" => $e->getLine(), "file" => $e->getFile()]);
                } else{
                    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                }
            }
            break;
        case 'addAttachment':
            //Get the attachment ids from the request as an array
            $attachmentId = $_POST['attachment'] ?? null;
            $post = PostAnswerRepository::getPostAnswerById($id);
            try {
                if ($attachmentId != null) {
                    addAttachment($post, $attachmentId);
                    echo json_encode(["status" => "success", "message" => "Adjunto añadido correctamente"]);
                } else {
                    throw new PostException("No se ha especificado el archivo a adjuntar");
                }
            } catch (PostException $e) {
                if (DEBUG_MODE){
                    echo json_encode(["status" => "error", "message" => $e->getMessage(), "line" => $e->getLine(), "file" => $e->getFile()]);
                } else{
                    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                }
            }
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Método no soportado"]);
            break;
    }

} catch (DataNotFoundException $e) {
    if (DEBUG_MODE){
        echo json_encode(["status" => "error", "message" => $e->getMessage(), "line" => $e->getLine(), "file" => $e->getFile()]);
    } else{
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}


/**
 * @param string $content
 * @param PostEntity $post
 * @param UserEntity $user
 * @return PostAnswerEntity
 * @throws PostException
 */
function insertAnswer(string $content, PostEntity $post, UserEntity $user): PostAnswerEntity
{
    // Check the content length
    if (strlen($content) < 10)
        throw new PostException("El contenido del post debe tener al menos 10 caracteres");

    // Check if the content exceeds the max length of text in the database
    if (strlen($content) > 65535)
        throw new PostException("El contenido del post no puede superar los 65535 caracteres");

    // Create the answer
    $answer = new PostAnswerEntity($user, $post, $content, 0);

    // Insert the answer
    PostAnswerRepository::createPostAnswer($answer);

    return $answer;

}


/**
 * @param PostAnswerEntity $post
 * @return void
 * @throws PostException
 */
function deleteAnswer(PostAnswerEntity $post): void
{
    if ($post->getAuthor()->getId() != $_SESSION['user']->getId())
        throw new PostException("No tienes permisos para eliminar esta respuesta");
    PostAnswerRepository::deletePostAnswer($post);
}


/**
 * @param PostAnswerEntity $post
 * @param int $attachmentId
 * @return void
 * @throws DataNotFoundException
 * @throws PostException
 */
function addAttachment(PostAnswerEntity $post, int $attachmentId): void
{
    //Check if the attachment exists and that it is not already attached to the post and that the user is the owner of the post and the attachment
    $attachment = AttachmentRepository::getAttachmentById($attachmentId);
    $uploaderId = is_null($attachment->getUploadedBy()) ? -1 : $attachment->getUploadedBy()->getId();
    if ($uploaderId != $_SESSION['user']->getId())
        throw new PostException("No tienes permisos para adjuntar este archivo");

    if ($post->getAuthor()->getId() != $_SESSION['user']->getId())
        throw new PostException("No tienes permisos para adjuntar archivos a esta respuesta");

    if (count($post->getAttachments()) >= 3)
        throw new PostException("No puedes adjuntar más de 3 archivos a una respuesta");
    foreach ($post->getAttachments() as $attached) {
        if ($attached->getId() == $attachmentId)
            throw new PostException("El archivo ya está adjunto a la respuesta");
    }

    //Attach the attachment to the post
    PostAnswerRepository::addAttachment($post, $attachment);
}


/**
 * @param PostEntity $post
 * @return array
 */
function getAnswers(PostEntity $post): array {
    try {
        $answerList = PostAnswerRepository::getPostAnswersByPost($post);
        $answers = [];
        foreach ($answerList as $answer) {
            $answer->setAttachments(PostAnswerRepository::getAttachments($answer));
            $answerSerialized = $answer->toArray();
            $answerSerialized['attachments'] = [];
            foreach ($answer->getAttachments() as $attachment) {
                $answerSerialized['attachments'][] = $attachment->toArray();
            }
            $answers[] = $answerSerialized;
        }
       usort($answers, function ($a, $b) {
            return $b['upvotes'] - $a['upvotes'];
        });
    } catch (DataNotFoundException) {
        return [];
    }

    return $answers;
}

