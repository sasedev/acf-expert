<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MBSale
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\MBSaleRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"month", "year", "company"}, errorPath="month", groups={"month"})
 *         @UniqueEntity(fields={"ref", "company"}, errorPath="ref", groups={"ref"})
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
