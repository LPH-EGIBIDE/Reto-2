<?php

namespace Repositories;

use Entities\NotificationEntity;
use Entities\UserEntity;
use Db\Db;
use Exceptions\DataNotFoundException;

abstract class NotificationRepository
{
    /**
     * @throws DataNotFoundException
     */
    public static function getNotificationById(int $id): NotificationEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Notificación no encontrada");
        }
        $notificationEntity = new NotificationEntity($result->text, $result->dismissed, $result->href, $result->type, UserRepository::getUserById($result->user_id));
        $notificationEntity->setId($result->id);
        return $notificationEntity;
    }

    /**
     * @param UserEntity $userEntity
     * @return NotificationEntity[]
     */
    public static function getNotificationsByUser(UserEntity $userEntity): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE id = :id");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute([
            ":id" => $userEntity->getId()
        ]);
        $result = $stmt->fetchAll();
        $notifications = [];
        foreach ($result as $notification) {
            $notificationEntity = new NotificationEntity($notification->text, $notification->dismissed, $notification->href, $notification->type, $userEntity);
            $notificationEntity->setId($notification->id);
            $notifications[] = $notificationEntity;
        }
        return $notifications;
    }


    /**
     * @param NotificationEntity $notificationEntity
     * @param UserEntity $userEntity
     * @return bool
     */
    public static function insertNotification(NotificationEntity $notificationEntity, UserEntity $userEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO notifications (text, dismissed, href, type, user) VALUES (:text, :dismissed, :href, :type, :user_id)");
        return $stmt->execute([
            ":text" => $notificationEntity->getText(),
            ":dismissed" => $notificationEntity->isDismissed(),
            ":href" => $notificationEntity->getHref(),
            ":type" => $notificationEntity->getType(),
            ":user_id" => $userEntity->getId()
        ]);
    }

    /**
     * @param NotificationEntity $notificationEntity
     * @return bool
     */
    public static function updateNotification(NotificationEntity $notificationEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE notifications SET text = :text, dismissed = :dismissed, href = :href, type = :type WHERE id = :id");
        return $stmt->execute([
            ":text" => $notificationEntity->getText(),
            ":dismissed" => $notificationEntity->isDismissed(),
            ":href" => $notificationEntity->getHref(),
            ":type" => $notificationEntity->getType(),
            ":id" => $notificationEntity->getId()
        ]);
    }

    /**
     * @param NotificationEntity $notificationEntity
     * @return bool
     */
    public static function deleteNotification(NotificationEntity $notificationEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM notifications WHERE id = :id");
        return $stmt->execute([
            ":id" => $notificationEntity->getId()
        ]);
    }

    /**
     * @param NotificationEntity $notificationEntity
     * @return bool
     */
    public static function dismissNotification(NotificationEntity $notificationEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE notifications SET dismissed = 1 WHERE id = :id");
        return $stmt->execute([
            ":id" => $notificationEntity->getId()
        ]);
    }




}