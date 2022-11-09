<?php

namespace Entities;

class NotificationEntity
{
    private int $id;
    private string $text;
    private int $dismissed;
    private string $href;
    private int $type;
    private int $userId;

    public function __construct(string $text, int $dismissed, string $href, int $type, int $userId) {
        $this->text = $text;
        $this->dismissed = $dismissed;
        $this->href = $href;
        $this->type = $type;
        $this->userId = $userId;
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getDismissed(): int
    {
        return $this->dismissed;
    }

    /**
     * @param int $dismissed
     */
    public function setDismissed(int $dismissed): void
    {
        $this->dismissed = $dismissed;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

}