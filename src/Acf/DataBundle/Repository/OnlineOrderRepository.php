<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineOrderRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('o')->select('count(o)');
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
        $qb = $this->createQueryBuilder('o')->orderBy('o.dtCrea', 'DESC');
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
     * @param User $user
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countByUser(User $user)
    {
        $qb = $this->createQueryBuilder('o')->select('count(o)')->join('o.user', 'u')->where('u.id = :id')->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param User $user
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByUserQuery(User $user)
    {
        $qb = $this->createQueryBuilder('o')->join('o.user', 'u')->where('u.id = :id')->orderBy('o.dtCrea', 'DESC')->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param User $user
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByUser(User $user)
    {
        return $this->getAllByUserQuery($user)->execute();
    }
}