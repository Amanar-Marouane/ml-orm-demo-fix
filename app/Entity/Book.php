<?php

declare(strict_types=1);

namespace App\Entity;

use MonkeysLegion\Entity\Attributes\Entity;
use MonkeysLegion\Entity\Attributes\Field;
use MonkeysLegion\Entity\Attributes\OneToOne;
use MonkeysLegion\Entity\Attributes\OneToMany;
use MonkeysLegion\Entity\Attributes\ManyToOne;
use MonkeysLegion\Entity\Attributes\ManyToMany;
use MonkeysLegion\Entity\Attributes\JoinTable;

#[Entity]
class Book
{
    #[Field(type: 'INT', autoIncrement: true, primaryKey: true)]
    public int $id;

    #[ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    public ?Author $author = null;
    
    #[Field(type: 'string')]
    public string $title;

    #[Field(type: 'text')]
    public string $description;

    #[OneToOne(targetEntity: Detail::class, inversedBy: 'book')]
    public Detail $detail;

    /** @var Review[] */
    #[OneToMany(targetEntity: Review::class, mappedBy: 'book')]
    public array $reviews = [];

    public function __construct()
    {
        $this->reviews = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function removeAuthor(): self
    {
        $this->author = null;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDetail(): Detail
    {
        return $this->detail;
    }

    public function setDetail(?Detail $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function addReview(Review $item): self
    {
        $this->reviews[] = $item;
        return $this;
    }

    public function removeReview(Review $item): self
    {
        $this->reviews = array_filter($this->reviews, fn($i) => $i !== $item);
        return $this;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }
}