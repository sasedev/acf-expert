<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\MPaye;

/**
 * MSalaryRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class MSalaryRepository extends EntityRepository
{

    /**
     * All count
     *
     * @param MPaye $p
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countByMPaye(MPaye $p)
    {
        $qb = $this->createQueryBuilder('s')->select('count(s)')->join('s.paye', 'p')->where('p.id = :id')->setParameter('id', $p->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param MPaye $p
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByMPayeQuery(MPaye $p)
    {
        $qb = $this->createQueryBuilder('s')->join('s.paye', 'p')->where('p.id = :id')->orderBy('m.matricule', 'ASC')->setParameter('id', $p->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param MPaye $p
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByMPaye(MPaye $p)
    {
        return $this->getAllByMPayeQuery($p)->execute();
    }
}
