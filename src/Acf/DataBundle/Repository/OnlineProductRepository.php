<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\OnlineProduct;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineProductRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('p')->select('count(p)');
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
        $qb = $this->createQueryBuilder('p')->orderBy('p.dtCrea', 'DESC');
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

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countVisible()
    {
        $qb = $this->createQueryBuilder('p')->select('count(p)')->where('p.lockout = :lockout')->setParameter('lockout', OnlineProduct::LOCKOUT_UNLOCKED);
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllVisibleQuery()
    {
        $qb = $this->createQueryBuilder('p')->where('p.lockout = :lockout')->orderBy('p.dtCrea', 'DESC')->setParameter('lockout', OnlineProduct::LOCKOUT_UNLOCKED);
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllVisible()
    {
        return $this->getAllVisibleQuery()->execute();
    }
}