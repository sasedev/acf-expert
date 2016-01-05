<?php

namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\Lang;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LangRepository extends EntityRepository
{

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count()
	{
		$qb = $this->createQueryBuilder('l')->select('count(l)');
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
		$qb = $this->createQueryBuilder('l')
			->orderBy('l.locale', 'ASC');
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

	/**
	 * All count
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *		 \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countEnabled()
	{
		$qb = $this->createQueryBuilder('l')->select('count(l)')->where('l.status = :st')->setParameter('st', Lang::ST_ENABLED);
		$query = $qb->getQuery();

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for All Entities
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllEnabledQuery()
	{
		$qb = $this->createQueryBuilder('l')
			->where('l.status = :st')
			->orderBy('l.locale', 'ASC')
			->setParameter('st', Lang::ST_ENABLED);
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
	public function getAllEnabled()
	{
		return $this->getAllEnabledQuery()->execute();
	}
}
