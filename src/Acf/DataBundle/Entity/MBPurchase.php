<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @author sasedev
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\MBPurchaseRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"month", "year", "company"}, errorPath="month", groups={"month"})
 *         @UniqueEntity(fields={"ref", "company"}, errorPath="ref", groups={"ref"})
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
		return $this->setRef(sprintf("%04d", $this->getYear()) . '-' . sprintf("%02d", $this->getMonth()) . '-ACH');
	}
}
