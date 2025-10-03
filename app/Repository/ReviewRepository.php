<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Review;
use MonkeysLegion\Repository\EntityRepository;

class ReviewRepository extends EntityRepository
{
    protected string $table = 'review';
    protected string $entityClass = Review::class;
}
