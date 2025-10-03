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
class Author
{
    #[Field(type: 'INT', autoIncrement: true, primaryKey: true)]
    public int $id;

    #[Field(type: 'string')]
    public string $email;
    
    #[Field(type: 'string')]
    public string $name;
    
     /** @var Book[] */
    #[OneToMany(targetEntity: Book::class, mappedBy: 'author')]
    public array $books = [];
    
     /** @var Category[] */
    #[ManyToMany(targetEntity: Category::class, inversedBy: 'authors', joinTable: new JoinTable(name: 'author_category', joinColumn: 'author_id', inverseColumn: 'category_id'))]
    public array $categories = [];

    public function __construct()
    {
        $this->books = [];
        $this->categories = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
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

    public function addBook(Book $item): self
    {
        $this->books[] = $item;
        return $this;
    }

    public function removeBook(Book $item): self
    {
        $this->books = array_filter($this->books, fn($i) => $i !== $item);
        return $this;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function addCategory(Category $item): self
    {
        $this->categories[] = $item;
        return $this;
    }

    public function removeCategory(Category $item): self
    {
        $this->categories = array_filter($this->categories, fn($i) => $i !== $item);
        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}