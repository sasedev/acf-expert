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
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
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
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByUser(User $user)
    {
        return $this->getAllByUserQuery($user)->execute();
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
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByCompany(Company $company)
    {
        return $this->getAllByCompanyQuery($company)->execute();
    }
}