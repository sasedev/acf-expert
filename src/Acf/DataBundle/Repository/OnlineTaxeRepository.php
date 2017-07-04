<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\OnlineTaxe;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineTaxeRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('t')->select('count(t)');
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
        $qb = $this->createQueryBuilder('t')->orderBy('t.priority', 'ASC');
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
        $qb = $this->createQueryBuilder('t')->select('count(t)')->where('t.visible = :visible')->setParameter('visible', OnlineTaxe::VISIBLE_SHOW);
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
        $qb = $this->createQueryBuilder('t')->where('t.visible = :visible')->orderBy('t.priority', 'ASC')->setParameter('visible', OnlineTaxe::VISIBLE_SHOW);
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