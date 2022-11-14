<?php

require_once '../../../config.inc.php';

session_start();

use Entities\PostEntity;
use Entities\UserEntity;
use Exceptions\DataNotFoundException;
use Exceptions\PostException;
use Repositories\PostRepository;
use Repositories\PostTopicRepository;

function insertPost(string $title, string $description, int $topic, UserEntity $user): void {
    if (empty($title) || empty($description) || empty($topic)) {
        throw new PostException("Los campos no pueden estar vacíos");
    }
    if(strlen($title) > 256){
        throw new PostException("El título no puede tener más de 256 caracteres");
    }
    if (strlen($description) > 65535) {
        throw new PostException("La descripción no puede tener más de 65535 caracteres");
    }
    try {
        $topic = PostTopicRepository::getPostTopicById($topic);
    }
    catch (DataNotFoundException $e){
        throw new PostException("El tema seleccionado no existe");
    }

    $post = new PostEntity($title, $description,0, $topic, $user,true, new DateTime());

    PostRepository::createPost($post);
}

function showPost(int $id): void {
    try {
        $post = PostRepository::getPostById($id);
    }
    catch (DataNotFoundException $e){
        throw new PostException("El post no existe");
    }
    $post->setViews($post->getViews() + 1);
    PostRepository::updatePost($post);
    echo json_encode($post);
}
