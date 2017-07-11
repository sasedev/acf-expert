<?php
namespace Acf\DataBundle\Entity;

/**
 * MBSale
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MBSale extends MonthlyBalance
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
     * @return MBSale
     */
    public function generateRef()
    {
        return $this->setRef(sprintf('%04d', $this->getYear()) . '-' . sprintf('%02d', $this->getMonth()) . '-VTE');
    }
}
