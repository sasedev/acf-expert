<?php
namespace Acf\DataBundle\Entity;

/**
 * MBPurchase
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MBPurchase extends MonthlyBalance
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generate $ref
     *
     * @return MBPurchase
     */
    public function generateRef()
    {
        return $this->setRef(sprintf('%04d', $this->getYear()) . '-' . sprintf('%02d', $this->getMonth()) . '-ACH');
    }
}
