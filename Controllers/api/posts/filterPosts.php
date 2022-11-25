<?php
require_once '../../../config.inc.php';


use Exceptions\DataNotFoundException;
use Repositories\PostRepository;
use Utils\AuthUtils;


session_start();
header('Content-Type: application/json');
if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$offset = intval($_GET['offset'] ?? $_POST['offset'] ?? 20);
$startFrom = intval($_GET['startFrom'] ?? $_POST['startFrom'] ??  0);
$title = $_GET['title'] ?? $_POST['title'] ?? "";
$topic = intval($_GET['topic'] ?? $_POST['topic'] ?? -1) == -1 ? null : intval($_GET['topic'] ?? $_POST['topic'] ?? -1);
$sort = $_GET['sort'] ?? "DATE";
$sortOrder = $_GET['sortOrder'] ?? "DESC";

$data =[];
try {
    $posts = PostRepository::filterPosts($offset, $startFrom, $title, $topic, $sort, $sortOrder);
    $data['posts'] = $posts;
    $data['postCount'] = count($posts);
    $data['status'] = "success";

} catch (DataNotFoundException $e) {
    die(json_encode(["status" => "error", "message" => $e->getMessage()]));
}

echo json_encode($data);