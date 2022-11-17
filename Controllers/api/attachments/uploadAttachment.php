<?php

use Entities\AttachmentEntity;
use Repositories\AttachmentRepository;
use Utils\AuthUtils;

require '../../../config.inc.php';

session_start();

if (!AuthUtils::checkAuth())
    die(json_encode(["status" => "error", "message" => "No hay sesión iniciada"]));

//Upload a file, set a random name and return the name. If the file is not uploaded, return an error message

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $imageTypes = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "gif" => "image/gif",
            "webp" => "image/webp",
            "svg" => "image/svg+xml",
            "pdf" => "application/pdf"
        ];
        $defaultType = "application/octet-stream";


        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid('', true) . ".bin";
                $fileDestination = getcwd().'/uploads/' . $fileNameNew;
                $fileType = $imageTypes[$fileActualExt] ?? $defaultType;
                move_uploaded_file($fileTmpName, $fileDestination);
                // Add a new attachment to the database
                $attachment = new AttachmentEntity($fileName, $fileNameNew, $fileType, new DateTime(), $_SESSION['user'], $_POST['public'] ?? false);
                $id = AttachmentRepository::insertAttachment($attachment);

                echo json_encode(["status" => "ok", "message" => "Archivo subido correctamente", "attachmentId" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "El archivo es demasiado grande"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Hubo un error al subir el archivo"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se ha subido ningún archivo"]);
    }