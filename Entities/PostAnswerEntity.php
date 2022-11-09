<?php

namespace Entities;

class PostAnswerEntity
{
    private int $id;
    private int $author;
    private int $post;
    private string $message;
    private int $upvotes;

    public function __construct(int $author, int $post, string $message, int $upvotes)
    {
        $this->author = $author;
        $this->post = $post;
        $this->message = $message;
        $this->upvotes = $upvotes;
    }

    // Getters and setters
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor(int $author): void
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getPost(): int
    {
        return $this->post;
    }

    /**
     * @param int $post
     */
    public function setPost(int $post): void
    {
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getUpvotes(): int
    {
        return $this->upvotes;
    }

    /**
     * @param int $upvotes
     */
    public function setUpvotes(int $upvotes): void
    {
        $this->upvotes = $upvotes;
    }


}