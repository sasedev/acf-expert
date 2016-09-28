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
   * All count
   *
   * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
   *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
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
    $qb = $this->createQueryBuilder('p')->orderBy('p.dtCrea', 'ASC');
    $query = $qb->getQuery();

    return $query;
  }

  /**
   * Get All Entities
   *
   * @return Ambigous <\Doctrine\ORM\mixed,
   *         \Doctrine\ORM\Internal\Hydration\mixed,
   *         \Doctrine\DBAL\Driver\Statement,
   *         \Doctrine\Common\Cache\mixed>
   */
  public function getAll()
  {
    return $this->getAllQuery()->execute();
  }

  /**
   * All count
   *
   * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
   *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
   */
  public function countByOrder(OnlineOrder $order)
  {
    $qb = $this->createQueryBuilder('p')
      ->select('count(p)')
      ->join('p.order', 'o')
      ->where('o.id = :id')
      ->setParameter('id', $order->getId());
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
   * @return Ambigous <\Doctrine\ORM\mixed,
   *         \Doctrine\ORM\Internal\Hydration\mixed,
   *         \Doctrine\DBAL\Driver\Statement,
   *         \Doctrine\Common\Cache\mixed>
   */
  public function getAllByOrder(OnlineOrder $order)
  {
    return $this->getAllByOrderQuery($order)->execute();
  }
}