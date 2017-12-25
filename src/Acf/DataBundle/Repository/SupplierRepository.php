<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Company;

/**
 * SupplierRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class SupplierRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @param Company $company
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByCompanyQuery(Company $company)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.company', 'c')
            ->where('c.id = :id')
            ->orderBy('s.label', 'ASC')
            ->setParameter('id', $company->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param Company $company
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByCompany(Company $company)
    {
        return $this->getAllByCompanyQuery($company)->execute();
    }
}
