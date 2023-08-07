<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * TagRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class TagRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
}
