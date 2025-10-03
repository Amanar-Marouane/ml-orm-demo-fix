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
class Category
{
    #[Field(type: 'INT', autoIncrement: true, primaryKey: true)]
    public int $id;

    #[Field(type: 'string', length: 255)]
    public string $name;

    #[Field(type: 'string', length: 500)]
    public string $description;

    /** @var Author[] */
    #[ManyToMany(targetEntity: Author::class, mappedBy: 'categories')]
    public array $authors = [];

    public function __construct()
    {
        $this->authors = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function addAuthor(Author $item): self
    {
        $this->authors[] = $item;
        return $this;
    }

    public function removeAuthor(Author $item): self
    {
        $this->authors = array_filter($this->authors, fn($i) => $i !== $item);
        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }
}
