<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ConstantFloatRepository extends EntityRepository
{

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('c')->select('count(c)');
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
		$qb = $this->createQueryBuilder('c')
			->orderBy('c.name', 'ASC');
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
	public function getAll()
	{
		return $this->getAllQuery()->execute();
	}
}
