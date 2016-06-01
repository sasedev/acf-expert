<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\User;

/**
 * CompanyUserRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyUserRepository extends EntityRepository
{

    /**
     * All count
     *
     * @param Company $company
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function countByCompany(Company $company)
    {
        $qb = $this->createQueryBuilder('cu')
            ->select('count(cu)')
            ->join('cu.company', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $company->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param Company $company
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByCompanyQuery(Company $company)
    {
        $qb = $this->createQueryBuilder('cu')
            ->join('cu.company', 'c')
            ->where('c.id = :id')
            ->orderBy('cu.dtCrea', 'ASC')
            ->setParameter('id', $company->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param Company $company
     *
     * @return Ambigous <\Doctrine\ORM\mixed,
     *         \Doctrine\ORM\Internal\Hydration\mixed,
     *         \Doctrine\DBAL\Driver\Statement,
     *         \Doctrine\Common\Cache\mixed>
     */
    public function getAllByCompany(Company $company)
    {
        return $this->getAllByCompanyQuery($company)->execute();
    }

    /**
     * All count
     *
     * @param User $user
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function countByUser(User $user)
    {
        $qb = $this->createQueryBuilder('cu')
            ->select('count(cu)')
            ->join('cu.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId());
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
        $qb = $this->createQueryBuilder('cu')
            ->join('cu.user', 'c')
            ->where('u.id = :id')
            ->orderBy('cu.dtCrea', 'ASC')
            ->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param User $user
     *
     * @return Ambigous <\Doctrine\ORM\mixed,
     *         \Doctrine\ORM\Internal\Hydration\mixed,
     *         \Doctrine\DBAL\Driver\Statement,
     *         \Doctrine\Common\Cache\mixed>
     */
    public function getAllByUser(User $user)
    {
        return $this->getAllByUserQuery($user)->execute();
    }
}
