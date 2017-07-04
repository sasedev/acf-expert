<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AutoincRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('a')->select('count(a)');
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('a')->orderBy('a.name', 'ASC');
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
