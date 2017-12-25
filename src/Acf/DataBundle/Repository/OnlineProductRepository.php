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
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllVisibleQuery()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.lockout = :lockout')
            ->orderBy('p.dtCrea', 'DESC')
            ->setParameter('lockout', OnlineProduct::LOCKOUT_UNLOCKED);
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