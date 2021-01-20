<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class PostRepository extends EntityRepository
{

    public function findByTagSlug(string $slug, int $limit,int $offset): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT bp FROM bizbink\BlogBundle\Entity\Post bp
            JOIN bp.tags pt
            WHERE pt.slug = :slug
            ORDER BY bp.created DESC'
        )->setParameter('slug', $slug)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $query->getResult();
    }
}
