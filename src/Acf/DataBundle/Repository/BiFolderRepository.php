<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\BiFolder;

/**
 * BiFolderRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class BiFolderRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getRootsQuery()
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.parent is NULL')
            ->orderBy('d.title', 'ASC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getRoots()
    {
        return $this->getRootsQuery()->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('b')->orderBy('b.dtCrea', 'DESC');
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
     * @param BiFolder $dg
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllChildsQuery(BiFolder $dg)
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.pageUrlFull LIKE :url')
            ->andWhere('d.id != :did')
            ->orderBy('d.pageUrlFull', 'ASC')
            ->setParameter('url', $dg->getPageUrlFull() . '%')
            ->setParameter('did', $dg->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param BiFolder $dg
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllChilds(BiFolder $dg)
    {
        return $this->getAllChildsQuery($dg)->execute();
    }
}