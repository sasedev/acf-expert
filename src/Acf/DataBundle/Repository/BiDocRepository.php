<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\BiFolder;

/**
 * BiDocRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class BiDocRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    /**
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('b')->select('count(b)');
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
     * All count
     *
     * @param BiFolder $f
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countByFolder(BiFolder $f)
    {
        $qb = $this->createQueryBuilder('b')->select('count(b)')->join('b.folder', 'f')->where('f.id = :id')->setParameter('id', $f->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param BiFolder $f
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByFolderQuery(BiFolder $f)
    {
        $qb = $this->createQueryBuilder('b')->join('b.folder', 'f')->where('f.id = :id')->orderBy('b.title', 'ASC')->setParameter('id', $f->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param BiFolder $f
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByFolder(BiFolder $f)
    {
        return $this->getAllByFolderQuery($f)->execute();
    }
}
