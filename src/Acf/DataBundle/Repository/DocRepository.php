<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\Company;

/**
 * DocRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocRepository extends EntityRepository
{
	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countByCompany(Company $c)
	{
		$qb = $this->createQueryBuilder('d')->select('count(d)')->join('d.company', 'c')->where('c.id = :id')->setParameter('id', $c->getId());
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
		$qb = $this->createQueryBuilder('d')
			->join('d.company', 'c')
			->where('c.id = :id')
			->orderBy('d.dtCrea', 'DESC')
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
