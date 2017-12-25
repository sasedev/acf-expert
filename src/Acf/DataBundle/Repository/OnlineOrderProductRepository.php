<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineOrder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineOrderProductRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('p')->orderBy('p.dtCrea', 'ASC');
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
    public function getAllByOrderQuery(OnlineOrder $order)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.order', 'o')
            ->where('o.id = :id')
            ->setParameter('id', $order->getId())
            ->orderBy('p.dtCrea', 'ASC');
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