<?php
require_once '../../../config.inc.php';


use Exceptions\DataNotFoundException;
use Repositories\PostRepository;
use Utils\AuthUtils;


session_start();
header('Content-Type: application/json');
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$offset = $_GET['offset'] ?? 20;
$startFrom = $_GET['startFrom'] ?? 1;
$title = $_GET['title'] ?? "";
$topic = $_GET['topic'] ?? null;
$sort = $_GET['sort'] ?? "DATE";
$sortOrder = $_GET['sortOrder'] ?? "DESC";

$data =[];
$data['pages'] = ceil(PostRepository::getPostsCount() / $offset);
try {
    $posts = PostRepository::filterPosts($offset, $startFrom, $title, $topic, $sort, $sortOrder);
} catch (DataNotFoundException $e) {
    die(json_encode(["status" => "error", "message" => $e->getMessage()]));
}

echo json_encode($posts);