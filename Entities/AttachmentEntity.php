<?php

namespace Entities;

use Cassandra\Date;

class AttachmentEntity
{
    private int $id;
    private string $filename;
    private string $filepath;
    private string $contentType;
    private Date $uploadedAt;
    private int $uploadedBy;
    private int $public;

    public function __construct(string $filename, string $filepath, string $contentType, Date $uploadedAt, int $uploadedBy, int $public)
    {
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->contentType = $contentType;
        $this->uploadedAt = $uploadedAt;
        $this->uploadedBy = $uploadedBy;
        $this->public = $public;
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
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath(string $filepath): void
    {
        $this->filepath = $filepath;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return Date
     */
    public function getUploadedAt(): Date
    {
        return $this->uploadedAt;
    }

    /**
     * @param Date $uploadedAt
     */
    public function setUploadedAt(Date $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }

    /**
     * @return int
     */
    public function getUploadedBy(): int
    {
        return $this->uploadedBy;
    }

    /**
     * @param int $uploadedBy
     */
    public function setUploadedBy(int $uploadedBy): void
    {
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * @return int
     */
    public function getPublic(): int
    {
        return $this->public;
    }

    /**
     * @param int $public
     */
    public function setPublic(int $public): void
    {
        $this->public = $public;
    }
}