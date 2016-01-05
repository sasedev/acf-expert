<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Company;

/**
 * CompanyFrameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyFrameRepository extends EntityRepository
{
	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countByCompany(Company $c)
	{
		$qb = $this->createQueryBuilder('cf')->select('count(cf)')->join('cf.company', 'c')->where('c.id = :id')->setParameter('id', $c->getId());
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
		$qb = $this->createQueryBuilder('cf')
			->join('cf.company', 'c')
			->where('c.id = :id')
			->orderBy('cf.lastName', 'ASC')
			->addOrderBy('cf.firstName', 'ASC')
			->setParameter('id', $c->getId());
		$query = $qb->getQuery();

		return $query;
	}

	/**
	 * Get All Entities
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *		 \Doctrine\ORM\Internal\Hydration\mixed,
	 *		 \Doctrine\DBAL\Driver\Statement,
	 *		 \Doctrine\Common\Cache\mixed>
	 */
	public function getAllByCompany(Company $c)
	{
		return $this->getAllByCompanyQuery($c)->execute();
	}
}
