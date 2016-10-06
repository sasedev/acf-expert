<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineInvoice;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('i')->select('count(i)');
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
        $qb = $this->createQueryBuilder('i')->orderBy('i.dtCrea', 'DESC');
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
     * @param User $user
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function countByUser(User $user)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('count(i)')
            ->join('i.user', 'u')
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
        $qb = $this->createQueryBuilder('i')
            ->join('i.user', 'u')
            ->where('u.id = :id')
            ->andWhere('i.status = :status')
            ->orderBy('i.dtCrea', 'DESC')
            ->setParameter('id', $user->getId())
            ->setParameter('status', OnlineInvoice::ST_OK);
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
        $qb = $this->createQueryBuilder('i')
            ->select('count(i)')
            ->join('i.company', 'c')
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
        $qb = $this->createQueryBuilder('i')
            ->join('i.company', 'c')
            ->where('c.id = :id')
            ->orderBy('i.dtCrea', 'DESC')
            ->setParameter('id', $company->getId());
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
    public function getAllByCompany(Company $company)
    {
        return $this->getAllByCompanyQuery($company)->execute();
    }
}