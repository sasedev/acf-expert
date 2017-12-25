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
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
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
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByInvoice(OnlineInvoice $invoice)
    {
        return $this->getAllByInvoiceQuery($invoice)->execute();
    }
}