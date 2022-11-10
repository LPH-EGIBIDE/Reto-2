<?php

namespace Repositories;

use Entities\UserEntity;
use Db\Db;
use Exceptions\DataNotFoundException;

abstract class UserRepository
{
    /**
     * @param int $id
     * @return UserEntity
     * @throws DataNotFoundException
     */
    public static function getUserById(int $id): UserEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        //Fetch as object
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Usuario no encontrado");
        }
        $userEntity = new UserEntity($result->username, $result->email, $result->password, $result->type, $result->profile_description, $result->active, $result->email_verified, $result->points, $result->mfa_type, $result->mfa_data);
        $userEntity->setId($result->id);
        return $userEntity;
    }
    
    /**
     * @param string $username
     * @return UserEntity
     * @throws DataNotFoundException
     */
    public static function getUserByUsername(string $username): UserEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        //Fetch as object
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Usuario no encontrado");
        }
        $userEntity = new UserEntity($result->username, $result->email, $result->password, $result->type, $result->profile_description, $result->active, $result->email_verified, $result->points, $result->mfa_type, $result->mfa_data);
        $userEntity->setId($result->id);
        return $userEntity;
    }

    /**
     * @param UserEntity $userEntity
     * @return bool
     */
    public static function insertUser(UserEntity $userEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO users (username, email, password, type, profile_description, active, email_verified, points, mfa_type, mfa_data)
            VALUES (:username, :email, :password, :type, :profile_description, :active, :email_verified, :points, :mfa_type, :mfa_data)");

        return $stmt->execute([
            ":username" => $userEntity->getUsername(),
            ":email" => $userEntity->getEmail(),
            ":password" => $userEntity->getPassword(),
            ":type" => $userEntity->getType(),
            ":profile_description" => $userEntity->getProfileDescription(),
            ":active" => intval($userEntity->isActive()),
            ":email_verified" => intval($userEntity->isEmailVerified()),
            ":points" => $userEntity->getPoints(),
            ":mfa_type" => $userEntity->getMfaType(),
            ":mfa_data" => $userEntity->getMfaData()

        ]);
    }

    /**
     * @param UserEntity $userEntity
     * @return bool
     */
    public static function updateUser(UserEntity $userEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password, type = :type, profile_description = :profile_description, active = :active, email_verified = :email_verified, points = :points, mfa_type = :mfa_type, mfa_data = :mfa_data WHERE id = :id");
        return $stmt->execute([
            ":id" => $userEntity->getId(),
            ":username" => $userEntity->getUsername(),
            ":email" => $userEntity->getEmail(),
            ":password" => $userEntity->getPassword(),
            ":type" => $userEntity->getType(),
            ":profile_description" => $userEntity->getProfileDescription(),
            ":active" => intval($userEntity->isActive()),
            ":email_verified" => intval($userEntity->isEmailVerified()),
            ":points" => $userEntity->getPoints(),
            ":mfa_type" => $userEntity->getMfaType(),
            ":mfa_data" => $userEntity->getMfaData()
        ]);
    }

    /**
     * @param UserEntity $userEntity
     * @return bool
     */
    public static function deleteUser(UserEntity $userEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([
            ":id" => $userEntity->getId()
        ]);
    }





}