<?php

namespace Pool\Core\Object;

class Note
{
    /** @var int|null */
    private $id;
    /** @var string */
    private $title;
    /** @var Text|null */
    private $text;
    /** @var \DateTime|null */
    private $created;
    /** @var \DateTime|null */
    private $lastUpdated;
    /** @var bool */
    private $submitted;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
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
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Text|null
     */
    public function getText(): ?Text
    {
        return $this->text;
    }

    /**
     * @param Text $text
     * @return void
     */
    public function setText(Text $text): void
    {
        $this->text = $text;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return void
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }

    /**
     * @param \DateTime $lastUpdated
     * @return void
     */
    public function setLastUpdated(\DateTime $lastUpdated): void
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * @return bool
     */
    public function getSubmitted(): bool
    {
        return $this->submitted;
    }

    /**
     * @param bool $submitted
     * @return void
     */
    public function setSubmitted(bool $submitted): void
    {
        $this->submitted = $submitted;
    }
}
