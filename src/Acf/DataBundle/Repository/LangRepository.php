<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\Lang;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LangRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('l')->orderBy('l.locale', 'ASC');
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
    public function getAllEnabledQuery()
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.status = :st')
            ->orderBy('l.locale', 'ASC')
            ->setParameter('st', Lang::ST_ENABLED);
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllEnabled()
    {
        return $this->getAllEnabledQuery()->execute();
    }
}
