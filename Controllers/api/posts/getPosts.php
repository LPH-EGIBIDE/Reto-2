<?php

require_once '../../../config.inc.php';
use Exceptions\DataNotFoundException;
use Repositories\PostRepository;
use Utils\AuthUtils;

header('Content-Type: application/json');

session_start();
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$offset = 10;
$data =[];
$data['pages'] = ceil(PostRepository::getPostsCount() / $offset);

if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $data['pages']) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

try {
    $posts = PostRepository::getAllPosts($offset, $offset * ($page - 1));
    foreach ($posts as $post) {
        $data['posts'][] = $post->toArray();
    }
    echo json_encode($data);
} catch (DataNotFoundException $e) {
    if (DEBUG_MODE) {
        echo json_encode(["status" => "error", "message" => "No hay posts", "line" => $e->getLine(), "file" => $e->getFile()]);
    } else {
        echo json_encode(["status" => "error", "message" => "No hay posts"]);
    }

    $data['posts'] = [];
}

