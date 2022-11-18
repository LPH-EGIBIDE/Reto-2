<?php

namespace Repositories;

use DateTime;
use Db\Db;
use Entities\PostEntity;
use Entities\PostTopicEntity;
use Entities\UserEntity;
use Exceptions\DataNotFoundException;

abstract class PostRepository
{

    /**
     * @throws DataNotFoundException
     */
    public static function getPostById(int $id): PostEntity
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result === false) {
            throw new DataNotFoundException("Post no encontrado");
        }
        $postEntity = new PostEntity($result->title, $result->description, $result->views, PostTopicRepository::getPostTopicById($result->topic), UserRepository::getUserById($result->author), $result->active, DateTime::createFromFormat("Y-m-d H:i:s", $result->date));
        $postEntity->setId($result->id);
        return $postEntity;
    }

    /**
     * @param PostEntity $postEntity
     * @return void
     */
    public static function updatePost(PostEntity $postEntity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("UPDATE posts SET title = :title, description = :description, views = :views, topic = :topic, author = :author, active = :active WHERE id = :id");
        $stmt->execute([
            ":title" => $postEntity->getTitle(),
            ":description" => $postEntity->getDescription(),
            ":views" => $postEntity->getViews(),
            ":topic" => $postEntity->getTopic()->getId(),
            ":author" => $postEntity->getAuthor()->getId(),
            ":active" => $postEntity->isActive(),
            ":id" => $postEntity->getId()
        ]);
        //Get the current date and time
        $postEntity->setDate(new \DateTime());
    }

    /**
     * @param PostEntity $postEntity
     * @return void
     */
    public static function deletePost(PostEntity $postEntity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([
            ":id" => $postEntity->getId()
        ]);
    }

    /**
     * @param PostEntity $postEntity
     * @return void
     */
    public static function createPost(PostEntity $postEntity): void
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO posts (title, description, views, topic, author, active, date) VALUES (:title, :description, :views, :topic, :author, :active, :date)");
        $stmt->execute([
            ":title" => $postEntity->getTitle(),
            ":description" => $postEntity->getDescription(),
            ":views" => $postEntity->getViews(),
            ":topic" => $postEntity->getTopic()->getId(),
            ":author" => $postEntity->getAuthor()->getId(),
            ":active" => $postEntity->isActive(),
            ":date" => $postEntity->getDate()->format("Y-m-d H:i:s")
        ]);
        // Get the current date and time
        $date = new \DateTime();
        $postEntity->setDate($date);
    }


    /**
     * @return PostEntity[]
     * @throws DataNotFoundException
     */
    public static function getAllPosts(int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts ORDER BY date DESC LIMIT :offset OFFSET :startFrom");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->bindValue(":startFrom", $startFrom, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) === 0) {
            throw new DataNotFoundException("No hay posts");
        }
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, DateTime::createFromFormat("Y-m-d H:i:s", $post->date));
            $postEntity->setId($post->id);
            $posts[] = $postEntity;
        }
        return $posts;
    }

    /**
     * @return PostEntity[]
     * @throws DataNotFoundException
     */
    public static function filterPosts(int $offset, int $startFrom, string $title = "", int $topic = null, int $author = null, string $sort = "DATE", string $sortOrder = "DESC"): array {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts WHERE TITLE LIKE :title AND DESCRIPTION LIKE :title AND TOPIC LIKE :topic AND AUTHOR LIKE :author ORDER BY :sort DESC LIMIT :offset OFFSET :startFrom");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->bindValue(":title", $title, \PDO::PARAM_INT);
        $stmt->bindValue(":topic", $topic, \PDO::PARAM_INT);
        $stmt->execute([
            ":title" => $title
        ]);
        $result = $stmt->fetchAll();
        if (count($result) === 0) {
            throw new DataNotFoundException("No se ha encontrado ningún post");
        }
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, DateTime::createFromFormat("Y-m-d H:i:s", $post->date));
            $postEntity->setId($post->id);
            $posts[] = $postEntity;
        }
        return $posts;
    }

    /**
     * @return PostEntity[]
     * @throws DataNotFoundException
     */
    public static function getPostsByUser(UserEntity $userEntity, int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts WHERE author = :author ORDER BY date DESC LIMIT :offset OFFSET :startFrom");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->bindValue(":offset", $offset, \PDO::PARAM_INT);
        $stmt->bindValue(":startFrom", $startFrom, \PDO::PARAM_INT);
        $stmt->execute([
            ":author" => $userEntity->getId(),
        ]);
        $result = $stmt->fetchAll();
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, DateTime::createFromFormat("Y-m-d H:i:s", $post->date));
            $postEntity->setId($post->id);
            $posts[] = $postEntity;
        }
        return $posts;
    }

    public static function getPostsCount(): int {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) AS count FROM posts");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result->count;
    }
}