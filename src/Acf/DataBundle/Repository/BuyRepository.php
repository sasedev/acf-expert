<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Transaction;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\DataBundle\Entity\Company;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * BuyRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuyRepository extends EntityRepository
{

    /**
     * achat ht du mois en cours
     *
     * @param Company $c
     * @param integer $year
     * @param integer $month
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function achatHtByCompanyInYearMonth(Company $c, $year, $month)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('sum(b.balanceTtc - b.vat - b.stamp)')
            ->join('b.monthlyBalance', 'm')
            ->join('m.company', 'c')
            ->where('c.id = :id')
            ->andWhere('m.year = :year')
            ->andWhere('m.month = :month')
            ->andWhere('(b.transactionStatus = :status1 OR b.transactionStatus = :status2)')
            ->setParameter('id', $c->getId())
            ->setParameter('year', \intval($year))
            ->setParameter('month', \intval($month))
            ->setParameter('status1', Transaction::STATUS_DONE)
            ->setParameter('status2', Transaction::STATUS_PENDING);
        $query = $qb->getQuery();

        $res = $query->getSingleScalarResult();
        if (null == $res) {
            $res = 0;
        }

        return $res;
    }

    /**
     * achat ht du mois en cours par nature d'achat
     *
     * @param CompanyNature $cn
     * @param integer       $year
     * @param integer       $month
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function achatHtByCompanyNatureInYearMonth(CompanyNature $cn, $year, $month)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('sum(b.balanceTtc - b.vat - b.stamp)')
            ->join('b.nature', 'n')
            ->join('b.monthlyBalance', 'm')
            ->where('n.id = :id')
            ->andWhere('m.year = :year')
            ->andWhere('m.month = :month')
            ->andWhere('(b.transactionStatus = :status1 OR b.transactionStatus = :status2)')
            ->setParameter('id', $cn->getId())
            ->setParameter('year', \intval($year))
            ->setParameter('month', \intval($month))
            ->setParameter('status1', Transaction::STATUS_DONE)
            ->setParameter('status2', Transaction::STATUS_PENDING);
        $query = $qb->getQuery();

        $res = $query->getSingleScalarResult();
        if (null == $res) {
            $res = 0;
        }

        return $res;
    }

    /**
     * achat ht de l'année en cours par nature d'achat
     *
     * @param CompanyNature $cn
     * @param integer       $year
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function achatHtByCompanyNatureInYear(CompanyNature $cn, $year)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('sum(b.balanceTtc - b.vat - b.stamp)')
            ->join('b.nature', 'n')
            ->join('b.monthlyBalance', 'm')
            ->where('n.id = :id')
            ->andWhere('m.year = :year')
            ->andWhere('(b.transactionStatus = :status1 OR b.transactionStatus = :status2)')
            ->setParameter('id', $cn->getId())
            ->setParameter('year', \intval($year))
            ->setParameter('status1', Transaction::STATUS_DONE)
            ->setParameter('status2', Transaction::STATUS_PENDING);
        $query = $qb->getQuery();

        $res = $query->getSingleScalarResult();
        if (null == $res) {
            $res = 0;
        }

        return $res;
    }

    /**
     * achat ht du mois en cours
     *
     * @param Company $c
     * @param integer $year
     * @param integer $month
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function achatPayedByCompanyInYearMonth(Company $c, $year, $month)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('sum(b.balanceNet)')
            ->join('b.monthlyBalance', 'm')
            ->join('m.company', 'c')
            ->where('c.id = :id')
            ->andWhere('m.year = :year')
            ->andWhere('m.month = :month')
            ->andWhere('b.transactionStatus = :status')
            ->andWhere('b.paymentType != :paymentType')
            ->setParameter('id', $c->getId())
            ->setParameter('year', \intval($year))
            ->setParameter('month', \intval($month))
            ->setParameter('status', Transaction::STATUS_DONE)
            ->setParameter('paymentType', Transaction::PTYPE_NA);
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * achat ht du mois en cours
     *
     * @param Company $c
     * @param integer $year
     * @param integer $month
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function achatNotPayedByCompanyInYearMonth(Company $c, $year, $month)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('sum(b.balanceTtc - b.vat - b.stamp)')
            ->join('b.monthlyBalance', 'm')
            ->join('m.company', 'c')
            ->where('c.id = :id')
            ->andWhere('m.year = :year')
            ->andWhere('m.month = :month')
            ->andWhere('(b.transactionStatus = :status1 OR b.transactionStatus = :status2)')
            ->andWhere('b.paymentType = :paymentType')
            ->setParameter('id', $c->getId())
            ->setParameter('year', \intval($year))
            ->setParameter('month', \intval($month))
            ->setParameter('status1', Transaction::STATUS_DONE)
            ->setParameter('status2', Transaction::STATUS_PENDING)
            ->setParameter('paymentType', Transaction::PTYPE_NA);
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * All count
     *
     * @param Company       $c
     * @param CompanyNature $cn
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function updateCompanyNatureNullByCompany(Company $c, CompanyNature $cn)
    {
        $rsm = new ResultSetMapping();
        $qb = $this->getEntityManager()->createNativeQuery('UPDATE acf_transactions SET nature_id =  ? FROM acf_transactions AS t INNER JOIN acf_company_mbalances AS m ON m.id = t.mbalance_id  WHERE m.company_id = ?  AND t.nature_id IS NULL AND t.transactiontype = ?', $rsm);
        $qb->setParameter(1, $cn->getId());
        $qb->setParameter(2, $c->getId());
        $qb->setParameter(3, Transaction::TTYPE_BUY);
        $qb->execute();
    }
}
