<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use MonkeysLegion\Repository\EntityRepository;

class AuthorRepository extends EntityRepository
{
    protected string $table = 'author';
    protected string $entityClass = Author::class;
}
