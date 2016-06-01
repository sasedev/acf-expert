<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class RoleRepository extends EntityRepository
{

    /**
     * Count All
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:, \Doctrine\DBAL\Driver\Statement,
     *         \Doctrine\Common\Cache\mixed>
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('r')->select('count(r)');
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
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.parents', 'p')
            ->leftJoin('r.childs', 'c')
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('r.name', 'ASC');
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
}
