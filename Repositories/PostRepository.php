<?php

namespace Repositories;

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
        $postEntity = new PostEntity($result->title, $result->description, $result->views, PostTopicRepository::getPostTopicById($result->topic), UserRepository::getUserById($result->author), $result->active, $result->date);
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
    public static function getAllPosts(int $offset = 15, $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts ORDER BY date DESC LIMIT :offset OFFSET :startFrom ORDER BY date DESC");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute([
            ":offset" => $offset,
            ":startFrom" => $startFrom
        ]);
        $result = $stmt->fetchAll();
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, $post->date);
            $postEntity->setId($post->id);
            $posts[] = $postEntity;
        }
        return $posts;
    }

    /**
     * @return PostEntity[]
     * @throws DataNotFoundException
     */
    public static function findPosts(PostTopicEntity $postTopicEntity = null, string $title = "", string $description = "", int $offset = 15, int $startFrom = 0): array
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM posts WHERE title LIKE :title AND description LIKE :description AND topic LIKE :topic ORDER BY date DESC LIMIT :startFrom OFFSET :offset");
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute([
            ":title" => "%" . $title . "%",
            ":description" => "%" . $description . "%",
            ":topic" => $postTopicEntity === null ? "%" : $postTopicEntity->getId(),
            ":startFrom" => $startFrom,
            ":offset" => $offset
        ]);
        $result = $stmt->fetchAll();
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, $post->date);
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
        $stmt->execute([
            ":author" => $userEntity->getId(),
            ":offset" => $offset,
            ":startFrom" => $startFrom
        ]);
        $result = $stmt->fetchAll();
        $posts = [];
        foreach ($result as $post) {
            $postEntity = new PostEntity($post->title, $post->description, $post->views, PostTopicRepository::getPostTopicById($post->topic), UserRepository::getUserById($post->author), $post->active, $post->date);
            $postEntity->setId($post->id);
            $posts[] = $postEntity;
        }
        return $posts;
    }






}