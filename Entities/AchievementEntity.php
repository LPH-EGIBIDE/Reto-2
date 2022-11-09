<?php

namespace Entities;

class AchievementEntity
{
    private int $id;
    private string $title;
    private string $description;
    private int $pointsAwarded;
    private int $photo;

    public function __construct(string $title, string $description, int $pointsAwarded, int $photo)
    {
        $this->title = $title;
        $this->description = $description;
        $this->pointsAwarded = $pointsAwarded;
        $this->photo = $photo;
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
    public function getPointsAwarded(): int
    {
        return $this->pointsAwarded;
    }

    /**
     * @param int $pointsAwarded
     */
    public function setPointsAwarded(int $pointsAwarded): void
    {
        $this->pointsAwarded = $pointsAwarded;
    }

    /**
     * @return int
     */
    public function getPhoto(): int
    {
        return $this->photo;
    }

    /**
     * @param int $photo
     */
    public function setPhoto(int $photo): void
    {
        $this->photo = $photo;
    }

}