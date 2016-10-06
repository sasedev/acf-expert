<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineInvoice;
use Acf\DataBundle\Entity\OnlineInvoiceDocument;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceDocumentRepository extends EntityRepository
{

    /**
     * All count
     *
     * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
     *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('d')->select('count(d)');
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
        $qb = $this->createQueryBuilder('d')->orderBy('d.dtCrea', 'ASC');
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
        $qb = $this->createQueryBuilder('d')
            ->select('count(d)')
            ->join('d.invoice', 'i')
            ->where('i.id = :id')
            ->andWhere('d.visible = :visible')
            ->setParameter('id', $invoice->getId())
            ->setParameter('visible', OnlineInvoiceDocument::ST_OK);
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
        $qb = $this->createQueryBuilder('d')
            ->join('d.invoice', 'i')
            ->where('i.id = :id')
            ->andWhere('d.visible = :visible')
            ->setParameter('id', $invoice->getId())
            ->setParameter('visible', OnlineInvoiceDocument::ST_OK)
            ->orderBy('d.dtCrea', 'ASC');
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