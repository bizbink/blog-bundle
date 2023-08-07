<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * CategoryRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class CategoryRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
}
