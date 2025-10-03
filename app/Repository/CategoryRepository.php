<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use MonkeysLegion\Repository\EntityRepository;

class CategoryRepository extends EntityRepository
{
    protected string $table = 'category';
    protected string $entityClass = Category::class;
}
