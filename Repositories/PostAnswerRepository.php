<?php

namespace Repositories;

use Db\Db;
use Entities\PostAnswerEntity;
use Entities\PostEntity;
use Entities\UserEntity;
use Exceptions\DataNotFoundException;

abstract class PostAnswerRepository
{


    /**
     * @param int $id
     * @return PostAnswerEntity
     * @throws DataNotFoundException
     */
    public static function getPostAnswerById(int $id): PostAnswerEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM post_answers WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        $postAnswerEntity = new PostAnswerEntity(
            UserRepository::getUserById($result->author),
            PostRepository::getPostById($result->post),
            $result->message,
            self::getPostUpvotes($id),
            AttachmentRepository::getAttachmentsForPostAnswer($id)
        );
        $postAnswerEntity->setId($result->id);
        return $postAnswerEntity;
    }

    /**
     * @param int $id
     * @return int
     * @throws DataNotFoundException
     */
    public static function getPostUpvotes(int $id): int
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as upvotes FROM post_upvotes WHERE post_answer = :id");
        $stmt->bindParam(":id", $id);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        return $result->upvotes;
    }

    /**
     * @param PostAnswerEntity $postAnswerEntity
     * @return void
     */
    public static function updatePostAnswer(PostAnswerEntity $postAnswerEntity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE post_answers SET message = :message WHERE id = :id");
        $stmt->execute([
            ":message" => $postAnswerEntity->getMessage(),
            ":id" => $postAnswerEntity->getId()
        ]);
    }

    /**
     * @param PostAnswerEntity $postAnswerEntity
     * @return void
     */
    public static function deletePostAnswer(PostAnswerEntity $postAnswerEntity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM post_answers WHERE id = :id");
        $stmt->execute([
            ":id" => $postAnswerEntity->getId()
        ]);
    }

    /**
     * @param PostAnswerEntity $postAnswerEntity
     * @return bool
     */
    public static function createPostAnswer(PostAnswerEntity $postAnswerEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO post_answers (author, post, message) VALUES (:author_id, :post_id, :message)");
        return $stmt->execute([
            ":author_id" => $postAnswerEntity->getAuthor()->getId(),
            ":post_id" => $postAnswerEntity->getPost()->getId(),
            ":message" => $postAnswerEntity->getMessage()
        ]);
    }


    /**
     * @param PostEntity $postEntity
     * @param int $offset
     * @param int $startFrom
     * @return array
     * @throws DataNotFoundException
     */
    public static function getPostAnswersByPost(PostEntity $postEntity, int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM post_answers WHERE post = :post_id LIMIT :offset OFFSET :start_from");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute([
            ":post_id" => $postEntity->getId(),
            ":offset" => $offset,
            ":start_from" => $startFrom
        ]);
        $result = $stmt->fetchAll();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        $postAnswers = [];
        foreach ($result as $postAnswer) {
            $postAnswerEntity = new PostAnswerEntity(
                UserRepository::getUserById($postAnswer->author),
                $postEntity,
                $postAnswer->message,
                self::getPostUpvotes($postAnswer->id),
            );
            $postAnswerEntity->setId($postAnswer->id);
            $postAnswers[] = $postAnswerEntity;
        }
        return $postAnswers;
    }


    /**
     * @param UserEntity $userEntity
     * @param int $offset
     * @param int $startFrom
     * @return array
     * @throws DataNotFoundException
     */
    public static function getPostAnswersByUser(UserEntity $userEntity, int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM post_answers WHERE author = :author_id LIMIT :offset OFFSET :start_from");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute(
            [
                ":author_id" => $userEntity->getId(),
                ":offset" => $offset,
                ":start_from" => $startFrom
            ]
        );
        $result = $stmt->fetchAll();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        $postAnswers = [];
        foreach ($result as $postAnswer) {
            $postAnswerEntity = new PostAnswerEntity(
                $userEntity,
                PostRepository::getPostById($postAnswer->post),
                $postAnswer->message,
                self::getPostUpvotes($postAnswer->id),
            );
            $postAnswerEntity->setId($postAnswer->id);
            $postAnswers[] = $postAnswerEntity;
        }
        return $postAnswers;
    }

    /**
     * @param UserEntity $userEntity
     * @param PostEntity $postEntity
     * @return array
     * @throws DataNotFoundException
     */
    public static function getPostAnswersByUserAndPost(UserEntity $userEntity, PostEntity $postEntity, int $offset = 15, int $startFrom = 15): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM post_answers WHERE author = :author_id AND post = :post_id");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute(
            [
                ":author_id" => $userEntity->getId(),
                ":post_id" => $postEntity->getId()
            ]
        );
        $result = $stmt->fetchAll();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        $postAnswers = [];
        foreach ($result as $postAnswer) {
            $postAnswerEntity = new PostAnswerEntity(
                $userEntity,
                $postEntity,
                $postAnswer->message,
                self::getPostUpvotes($postAnswer->id),
            );
            $postAnswerEntity->setId($postAnswer->id);
            $postAnswers[] = $postAnswerEntity;
        }
        return $postAnswers;
    }

    /**
     * @param UserEntity $userEntity
     * @param int $offset
     * @param int $startFrom
     * @return array
     * @throws DataNotFoundException
     */
    static function getUserFavouriteAnswers(UserEntity $userEntity, int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM post_answers, user_favourite_answers WHERE post_answers.id = user_favourite_answers.answer AND user_favourite_answers.user = :user_id LIMIT :offset OFFSET :start_from");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute(
            [
                ":author_id" => $userEntity->getId(),
                ":offset" => $offset,
                ":start_from" => $startFrom
            ]
        );
        $result = $stmt->fetchAll();
        if ($result === false) {
            throw new DataNotFoundException("Post answer no encontrado");
        }
        $postAnswers = [];
        foreach ($result as $postAnswer) {
            $postAnswerEntity = new PostAnswerEntity(
                $userEntity,
                PostRepository::getPostById($postAnswer->post),
                $postAnswer->message,
                self::getPostUpvotes($postAnswer->id),
            );
            $postAnswerEntity->setId($postAnswer->id);
            $postAnswers[] = $postAnswerEntity;
        }
        return $postAnswers;
    }

    /**
     * @param UserEntity $userEntity
     * @param PostAnswerEntity $postAnswerEntity
     * @return bool
     */
    static function addUserFavouriteAnswer(UserEntity $userEntity, PostAnswerEntity $postAnswerEntity): bool
{
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO user_favourite_answers (user, answer) VALUES (:user_id, :answer_id)");
        return $stmt->execute(
            [
                ":user_id" => $userEntity->getId(),
                ":answer_id" => $postAnswerEntity->getId()
            ]
        );
    }


    /**
     * @param UserEntity $userEntity
     * @param PostAnswerEntity $postAnswerEntity
     * @return void
     */
    public static function upvotePostAnswer(UserEntity $userEntity, PostAnswerEntity $postAnswerEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO post_upvotes (user, post_answer) VALUES (:user_id, :post_answer_id)");
        return $stmt->execute([
            ":user_id" => $userEntity->getId(),
            ":post_answer_id" => $postAnswerEntity->getId()
        ]);
    }

    /**
     * @param UserEntity $userEntity
     * @param PostAnswerEntity $postAnswerEntity
     * @return void
     */
    public static function downvotePostAnswer(UserEntity $userEntity, PostAnswerEntity $postAnswerEntity): bool
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM post_upvotes WHERE user = :user_id AND post_answer = :post_answer_id");
        return $stmt->execute([
            ":user_id" => $userEntity->getId(),
            ":post_answer_id" => $postAnswerEntity->getId()
        ]);
    }





}