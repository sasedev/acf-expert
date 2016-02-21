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
	 * chiffre d'affaire ht total du mois en cours
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function caHtTotalByCompanyInYearMonth(Company $c, $year, $month)
	{
		$qb = $this->createQueryBuilder('sv')->select('sum(sv.balanceTtc - sv.vat)')
			->join('sv.sale', 's')
			->join('s.account', 'a')
			->join('a.company', 'c')
			->join('s.monthlyBalance', 'm')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('(s.transactionStatus = :status1 OR s.transactionStatus = :status2)')
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
	 * chiffre d'affaire encaissé du mois en cours
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function caEncByCompanyInYearMonth(Company $c, $year, $month)
	{
		$qb = $this->createQueryBuilder('sv')->select('sum(sv.balanceNet)')
			->join('sv.sale', 's')
			->join('s.account', 'a')
			->join('a.company', 'c')
			->join('s.monthlyBalance', 'm')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('s.transactionStatus = :status')
			->andWhere('s.paymentType != :paymentType')
			->setParameter('id', $c->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status', Transaction::STATUS_DONE)
			->setParameter('paymentType', Transaction::PTYPE_NA);
		$query = $qb->getQuery();

		$res = $query->getSingleScalarResult();
		if (null == $res) {
			$res = 0;
		}
		return $res;
	}

	/**
	 * chiffre d'affaire non-encaissé du mois en cours
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function caNotEncByCompanyInYearMonth(Company $c, $year, $month)
	{
		$qb = $this->createQueryBuilder('sv')->select('sum(sv.balanceNet)')
			->join('sv.sale', 's')
			->join('s.account', 'a')
			->join('a.company', 'c')
			->join('s.monthlyBalance', 'm')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->andWhere('m.month = :month')
			->andWhere('(s.transactionStatus = :status1 OR s.transactionStatus = :status2)')
			->andWhere('s.paymentType = :paymentType')
			->setParameter('id', $c->getId())
			->setParameter('year', \intval($year))
			->setParameter('month', \intval($month))
			->setParameter('status1', Transaction::STATUS_DONE)
			->setParameter('status2', Transaction::STATUS_PENDING)
			->setParameter('paymentType', Transaction::PTYPE_NA);
		$query = $qb->getQuery();

		$res = $query->getSingleScalarResult();
		if (null == $res) {
			$res = 0;
		}
		return $res;
	}
}