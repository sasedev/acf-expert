<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Transaction;
use Acf\DataBundle\Entity\Account;
use Acf\DataBundle\Entity\Company;

/**
 *
 * @author sasedev
 */
class SecondaryVatRepository extends EntityRepository
{
	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function sumBalanceNetByAccountInYearMonth(Account $a, $year, $month)
	{
		$qb = $this->createQueryBuilder('sv')->select('sum(sv.balanceNet)')
			->join('sv.sale', 's')
			->join('s.account', 'a')
			->join('s.monthlyBalance', 'm')
			->where('a.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('s.transactionStatus = :status')
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
	public function sumBalanceHtByCompanyInYearMonth(Company $c, $year, $month)
	{
		$qb = $this->createQueryBuilder('sv')->select('sum(sv.balanceTtc - sv.vat)')
			->join('sv.sale', 's')
			->join('s.account', 'a')
			->join('a.company', 'c')
			->join('s.monthlyBalance', 'm')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('s.transactionStatus = :status')
			->setParameter('id', $c->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status', Transaction::STATUS_DONE);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}
}