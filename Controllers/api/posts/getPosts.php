<?php

require_once '../../../config.inc.php';
use Exceptions\DataNotFoundException;
use Repositories\PostRepository;

header('Content-Type: application/json');

session_start();
if (!\Utils\AuthUtils::checkAuth())
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
} catch (DataNotFoundException $e) {
    echo json_encode(["status" => "error", "message" => "No hay posts", "line" => $e->getLine(), "file" => $e->getFile()]);
    $data['posts'] = [];
}

echo json_encode($data);