<?php

use Repositories\PostRepository;

require_once '../../../config.inc.php';

session_start();
if (!\Utils\AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

$offset = $_GET['offset'] ?? 20;
$startFrom = $_GET['startFrom'] ?? 1;
$title = $_GET['title'] ?? "";
$topic = $_GET['topic'] ?? null;
$author = $_GET['author'] ?? null;
$sort = $_GET['sort'] ?? "DATE";
$sortOrder = $_GET['sortOrder'] ?? "DESC";

$data =[];
$data['pages'] = ceil(PostRepository::getPostsCount() / $offset);

PostRepository::filterPosts($offset, $startFrom, $title, $topic, $author, $sort, $sortOrder);