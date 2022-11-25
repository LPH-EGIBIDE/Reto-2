<?php

use Exceptions\DataNotFoundException;
use Repositories\AttachmentRepository;
use Utils\AuthUtils;

require '../../../config.inc.php';

session_start();

if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));
try {
    $tutorialList = AttachmentRepository::getTutorials();
    $tutorials = [];
    foreach ($tutorialList as $tutorial) {
        $author = $tutorial->getUploadedBy();
        $tutorial = $tutorial->toArray();
        $tutorial['uploadedBy'] = $author->toArray();
        $tutorials[] = $tutorial;
    }
    echo json_encode(["status" => "success", "tutorials" => $tutorials]);
} catch (DataNotFoundException $e) {
    if (DEBUG_MODE)
        die(json_encode(["status" => "error", "message" => $e->getMessage(), "debug" => $e->getMessage()]));
    else
        die(json_encode(["status" => "error", "message" => "No hay tutoriales"]));
}



