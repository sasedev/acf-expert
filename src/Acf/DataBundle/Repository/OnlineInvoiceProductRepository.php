<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineInvoice;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceProductRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('p')->select('count(p)');
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
        $qb = $this->createQueryBuilder('p')->orderBy('p.dtCrea', 'ASC');
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
    public function getAll()
    {
        return $this->getAllQuery()->execute();
    }

    /**
     * All count
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function countByInvoice(OnlineInvoice $invoice)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p)')
            ->join('p.invoice', 'i')
            ->where('i.id = :id')
            ->setParameter('id', $invoice->getId());
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByInvoiceQuery(OnlineInvoice $invoice)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.invoice', 'i')
            ->where('i.id = :id')
            ->setParameter('id', $invoice->getId())
            ->orderBy('p.dtCrea', 'ASC');
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
    public function getAllByInvoice(OnlineInvoice $invoice)
    {
        return $this->getAllByInvoiceQuery($invoice)->execute();
    }
}