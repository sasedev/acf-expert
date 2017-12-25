<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FeedReadRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class FeedReadRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('f')->orderBy('f.url', 'ASC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
    }
}
