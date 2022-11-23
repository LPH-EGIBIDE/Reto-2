<?php
require_once '../../../config.inc.php';


use Exceptions\DataNotFoundException;
use Repositories\PostRepository;
use Utils\AuthUtils;


session_start();
header('Content-Type: application/json');
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$offset = intval($_GET['offset']) ?? intval($_POST['offset']) ?? 20;
$startFrom = intval($_GET['startFrom']) ?? intval($_POST['startFrom']) ??  1;
$title = $_GET['title'] ?? $_POST['title'] ?? "";
$topic = intval($_GET['topic']) ?? intval($_POST['topic']) ?? null;
$user = intval($_GET['user']) ?? intval($_POST['user']) ?? null;
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