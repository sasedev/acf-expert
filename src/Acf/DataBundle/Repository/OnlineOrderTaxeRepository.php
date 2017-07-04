<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineOrder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineOrderTaxeRepository extends EntityRepository
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
    public function countByOrder(OnlineOrder $order)
    {
        $qb = $this->createQueryBuilder('t')->select('count(t)')->join('t.order', 'o')->where('o.id = :id')->setParameter('id', $order->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByOrderQuery(OnlineOrder $order)
    {
        $qb = $this->createQueryBuilder('t')->join('t.order', 'o')->where('o.id = :id')->setParameter('id', $order->getId())->orderBy('t.priority', 'ASC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByOrder(OnlineOrder $order)
    {
        return $this->getAllByOrderQuery($order)->execute();
    }
}