<?php

namespace bizbink\BlogBundle\Entity;

/**
 * EntriesRepository
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntryRepository extends \Doctrine\ORM\EntityRepository {

    public function findByPageOrderByIdReversed($page) {
        return $this->getEntityManager()
                        ->createQuery('SELECT e
                            FROM BlogBundle:Entry e
                            ORDER BY e.id DESC')
                        ->setFirstResult(($page - 1) * 5)
                        ->setMaxResults($page * 5)
                        ->getResult();
    }

    public function findAllByCategoryName($name, $page = NULL) {
        $queryBuilder = $this->createQueryBuilder('e')
                ->join('BlogBundle:Category', 'c', \Doctrine\ORM\Query\Expr\Join::INNER_JOIN, 'e.category = c.id')
                ->where('c.name = :name')
                ->orderBy('e.id', 'DESC');
        if (isset($page)) {
            $queryBuilder->setFirstResult(($page - 1) * 5);
            $queryBuilder->setMaxResults($page * 5);
        }
        return $queryBuilder->setParameter('name', $name)
                        ->getQuery()->getResult();
    }

    public function findAllByCategorySlug($slug, $page = NULL) {
        $queryBuilder = $this->createQueryBuilder('e')
                ->join('BlogBundle:Category', 'c', \Doctrine\ORM\Query\Expr\Join::INNER_JOIN, 'e.category = c.id')
                ->where('c.slug = :slug')
                ->orderBy('e.id', 'DESC');
        if (isset($page)) {
            $queryBuilder->setFirstResult(($page - 1) * 5);
            $queryBuilder->setMaxResults($page * 5);
        }
        return $queryBuilder->setParameter('slug', $slug)
                        ->getQuery()->getResult();
    }

    public function findAllByTagName($name, $page = NULL) {
        $queryBuilder = $this->createQueryBuilder('e')
                ->join('e.tags', 't')
                ->where('t.name = :name')
                ->orderBy('e.id', 'DESC');
        if (isset($page)) {
            $queryBuilder->setFirstResult(($page - 1) * 5);
            $queryBuilder->setMaxResults($page * 5);
        }
        return $queryBuilder->setParameter('name', $name)
                        ->getQuery()->getResult();
    }

    public function findAllByTagSlug($slug, $page = NULL) {
        $queryBuilder = $this->createQueryBuilder('e')
                ->join('e.tags', 't')
                ->where('t.slug = :slug')
                ->orderBy('e.id', 'DESC');
        if (isset($page)) {
            $queryBuilder->setFirstResult(($page - 1) * 5);
            $queryBuilder->setMaxResults($page * 5);
        }
        return $queryBuilder->setParameter('slug', $slug)
                        ->getQuery()->getResult();
    }

}
