<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Account;
use Acf\DataBundle\Entity\Transaction;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\DataBundle\Entity\Company;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * BuyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuyRepository extends EntityRepository
{
	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function sumBalanceNetByAccountInYearMonth(Account $a, $year, $month)
	{
		$qb = $this->createQueryBuilder('b')->select('sum(b.balanceNet)')
			->join('b.account', 'a')
			->join('b.monthlyBalance', 'm')
			->where('a.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('b.transactionStatus = :status')
			->setParameter('id', $a->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status', Transaction::STATUS_DONE);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function sumBalanceNetByCompanyInYearMonth(Company $c, $year, $month)
	{
		$qb = $this->createQueryBuilder('b')->select('sum(b.balanceNet)')
			->join('b.monthlyBalance', 'm')
			->join('m.company', 'c')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('b.transactionStatus = :status')
			->setParameter('id', $c->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status', Transaction::STATUS_DONE);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function sumBalanceHtByCompanyNatureInYearMonth(CompanyNature $cn, $year, $month)
	{
		$qb = $this->createQueryBuilder('b')->select('sum(b.balanceTtc - b.vat - b.stamp)')
			->join('b.nature', 'n')
			->join('b.monthlyBalance', 'm')
			->where('n.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('b.transactionStatus = :status')
			->setParameter('id', $cn->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status', Transaction::STATUS_DONE);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function sumBalanceHtByCompanyNatureInYear(CompanyNature $cn, $year)
	{
		$qb = $this->createQueryBuilder('b')->select('sum(b.balanceTtc - b.vat - b.stamp)')
			->join('b.nature', 'n')
			->join('b.monthlyBalance', 'm')
			->where('n.id = :id')
			->andWhere('m.year = :year')
			->andWhere('b.transactionStatus = :status')
			->setParameter('id', $cn->getId())
			->setParameter('year', \intval($year))
			->setParameter('status', Transaction::STATUS_DONE);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
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
