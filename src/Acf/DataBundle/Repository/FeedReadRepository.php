<?php

namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FeedReadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FeedReadRepository extends EntityRepository
{

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('f')->select('count(f)');
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
		$qb = $this->createQueryBuilder('f')
			->orderBy('f.url', 'ASC');
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

?>
