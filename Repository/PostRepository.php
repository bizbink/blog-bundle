<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Repository;

use bizbink\BlogBundle\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * PostRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByTagSlug(string $slug, int $limit, int $offset): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT bp FROM bizbink\BlogBundle\Entity\Post bp
            JOIN bp.tags pt
            WHERE pt.slug = :slug
            ORDER BY bp.created DESC'
        )
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('slug', $slug);

        return $query->getResult();
    }
}
