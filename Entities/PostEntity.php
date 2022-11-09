<?php

namespace Entities;

class PostEntity
{
    private int $id;
    private string $title;
    private string $description;
    private int $views;
    private int $topic;
    private int $author;
    private int $active;

    public function __construct(string $title, string $description, int $views, int $topic, int $author, int $active)
    {
        $this->title = $title;
        $this->description = $description;
        $this->views = $views;
        $this->topic = $topic;
        $this->author = $author;
        $this->active = $active;
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getTopic(): int
    {
        return $this->topic;
    }

    /**
     * @param int $topic
     */
    public function setTopic(int $topic): void
    {
        $this->topic = $topic;
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
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive(int $active): void
    {
        $this->active = $active;
    }


}