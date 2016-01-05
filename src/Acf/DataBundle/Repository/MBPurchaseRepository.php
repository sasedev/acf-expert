<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Company;

/**
 * MBPurchaseRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class MBPurchaseRepository extends EntityRepository
{

	/**
	 * All count
	 * 
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countByCompany(Company $c)
	{
		$qb = $this->createQueryBuilder('m')
			->select('count(m)')
			->join('m.company', 'c')
			->where('c.id = :id')
			->setParameter('id', $c->getId());
		$query = $qb->getQuery();
		
		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for All Entities
	 * 
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByCompanyQuery(Company $c)
	{
		$qb = $this->createQueryBuilder('m')
			->join('m.company', 'c')
			->where('c.id = :id')
			->orderBy('m.year', 'DESC')
			->addOrderBy('m.month', 'DESC')
			->setParameter('id', $c->getId());
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
	public function getAllByCompany(Company $c)
	{
		return $this->getAllByCompanyQuery($c)
			->execute();
	}

	/**
	 * Get Query for All Entities
	 * 
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllByYearCompanyQuery($year, Company $c)
	{
		$qb = $this->createQueryBuilder('m')
			->join('m.company', 'c')
			->where('c.id = :id')
			->andWhere('m.year = :year')
			->orderBy('m.month', 'ASC')
			->setParameter('id', $c->getId())
			->setParameter('year', $year);
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
	public function getAllByYearCompany($year, Company $c)
	{
		return $this->getAllByYearCompanyQuery($year, $c)
			->execute();
	}

	/**
	 * Get Query for All Entities
	 * 
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllYearByCompanyQuery(Company $c)
	{
		$qb = $this->createQueryBuilder('m')
			->select('m.year')
			->distinct()
			->join('m.company', 'c')
			->where('c.id = :id')
			->groupBy('m.year')
			->orderBy('m.year', 'DESC')
			->setParameter('id', $c->getId());
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
	public function getAllYearByCompany(Company $c)
	{
		return $this->getAllYearByCompanyQuery($c)
			->execute();
	}
}
