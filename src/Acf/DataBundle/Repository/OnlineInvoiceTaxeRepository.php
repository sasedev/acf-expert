<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\OnlineInvoice;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceTaxeRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('t')->orderBy('t.priority', 'ASC');
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
        $qb = $this->createQueryBuilder('t')
            ->join('t.invoice', 'i')
            ->where('i.id = :id')
            ->setParameter('id', $invoice->getId())
            ->orderBy('t.priority', 'ASC');
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