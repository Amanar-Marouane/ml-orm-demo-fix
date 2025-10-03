<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use MonkeysLegion\Repository\EntityRepository;

class BookRepository extends EntityRepository
{
    protected string $table = 'book';
    protected string $entityClass = Book::class;
}
