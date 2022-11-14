<?php

namespace Repositories;

use DateTime;
use Entities\AttachmentEntity;
use Entities\UserEntity;
use Db\Db;
use Exceptions\DataNotFoundException;

abstract class AttachmentRepository{

    /**
     * @throws DataNotFoundException
     */
    public static function getAttachmentById(int $id = -1): AttachmentEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT id, filename, filepath, content_type, UNIX_TIMESTAMP(uploaded_at) as uploaded_at, uploaded_by, public FROM attachments WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Archivo no encontrado");
        }
        try {
            $attachment = new AttachmentEntity($result["filename"], $result["filepath"], $result["content_type"], new DateTime("@" . $result["uploaded_at"]), $id != -1 ? UserRepository::getUserById($result["uploaded_by"]) : null, $result["public"]);
        } catch (\Exception $e) {
            if (DEBUG_MODE){
                throw new DataNotFoundException("Error loading attachment: " . $e->getMessage());
            }
            throw new DataNotFoundException("Archivo no encontrado");
        }
        $attachment->setId($result["id"]);
        return $attachment;
    }

    /**
     * @param AttachmentEntity $attachmentEntity
     * @return bool
     */
    public static function insertAttachment(AttachmentEntity $attachmentEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO attachments (filename, filepath, content_type, uploaded_at, uploaded_by, public) VALUES (:filename, :filepath, :content_type, :uploaded_at, :uploaded_by, :public)");

        return $stmt->execute([
            ":filename" => $attachmentEntity->getFilename(),
            ":filepath" => $attachmentEntity->getFilepath(),
            ":content_type" => $attachmentEntity->getContentType(),
            ":uploaded_at" => $attachmentEntity->getUploadedAt(),
            ":uploaded_by" => $attachmentEntity->getUploadedBy()->getId(),
            ":public" => $attachmentEntity->isPublic()
        ]);
    }

    /**
     * @param AttachmentEntity $attachmentEntity
     * @return bool
     */
    public static function updateAttachment(AttachmentEntity $attachmentEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE attachments SET filename = :filename, filepath = :filepath, content_type = :content_type, uploaded_at = :uploaded_at, uploaded_by = :uploaded_by, public = :public WHERE id = :id");

        return $stmt->execute([
            ":id" => $attachmentEntity->getId(),
            ":filename" => $attachmentEntity->getFilename(),
            ":filepath" => $attachmentEntity->getFilepath(),
            ":content_type" => $attachmentEntity->getContentType(),
            ":uploaded_at" => $attachmentEntity->getUploadedAt(),
            ":uploaded_by" => $attachmentEntity->getUploadedBy()->getId(),
            ":public" => $attachmentEntity->isPublic()
        ]);
    }

    /**
     * @param AttachmentEntity $attachmentEntity
     * @return bool
     */
    public static function deleteAttachment(AttachmentEntity $attachmentEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM attachments WHERE id = :id");

        return $stmt->execute([
            ":id" => $attachmentEntity->getId()
        ]);
    }


    /**
     * @throws DataNotFoundException
     */
    public static function getUserAvatar(UserEntity $userEntity, int $avatarId): AttachmentEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT id, filename, filepath, content_type, UNIX_TIMESTAMP(uploaded_at) as uploaded_at, uploaded_by, public FROM attachments WHERE id = :id");
        $stmt->bindParam(":id", $avatarId);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Archivo no encontrado");
        }
        try {
            $attachment = new AttachmentEntity($result["filename"], $result["filepath"], $result["content_type"], new DateTime("@" . $result["uploaded_at"]), $avatarId != -1 ? $userEntity : null, $result["public"]);
        } catch (\Exception $e) {
            if (DEBUG_MODE){
                throw new DataNotFoundException("Error loading attachment: " . $e->getMessage());
            }
            throw new DataNotFoundException("Archivo no encontrado");
        }
        $attachment->setId($result["id"]);
        return $attachment;
    }

    public static function getUserAttachments(UserEntity $userEntity): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM attachments WHERE uploaded_by = :uploaded_by");
        $stmt->execute(
            [
                ":uploaded_by" => $userEntity->getId()
            ]
        );
        $result = $stmt->fetchAll();
        $attachments = [];
        foreach ($result as $row) {
            $attachment = new AttachmentEntity($row["filename"], $row["filepath"], $row["content_type"], $row["uploaded_at"], $userEntity, $row["public"]);
            $attachment->setId($row["id"]);
            $attachments[] = $attachment;
        }
        return $attachments;
    }

    /**
     * @throws DataNotFoundException
     */
    public static function getAttachmentsForPostAnswer(int $postAnswerId): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM attachments, post_answer_attachments WHERE post_answers_id = id AND post_answers_id = :post_answers_id");
        $stmt->execute(
            [
                ":post_answer_id" => $postAnswerId
            ]
        );
        $result = $stmt->fetchAll();
        $attachments = [];
        foreach ($result as $row) {
            $attachment = new AttachmentEntity($row["filename"], $row["filepath"], $row["content_type"], $row["uploaded_at"], UserRepository::getUserById($row["uploaded_by"]), $row["public"]);
            $attachment->setId($row["id"]);
            $attachments[] = $attachment;
        }
        return $attachments;
    }

}