<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace bizbink\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class TagRepository extends EntityRepository {

    public function findOneByName($name) {
        $queryBuilder = $this->createQueryBuilder('t')
                ->where('t.name = :name')
                ->setFirstResult(0)
                ->setMaxResults(1)
                ->setParameter('name', $name);
        
        return $queryBuilder->getQuery()->getResult()[0];
    }

}
