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
class User
{
    #[Field(type: 'INT', autoIncrement: true, primaryKey: true)]
    public int $id;

    #[Field(type: 'string')]
    public string $email;
    
    #[Field(type: 'string')]
    public string $passwordHash;

    #[Field(type: 'string')]
    public string $name;
    
     /** @var Review[] */
    #[OneToMany(targetEntity: Review::class, mappedBy: 'user')]
    public array $reviews = [];

    public function __construct()
    {
        $this->reviews = [];
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

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
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