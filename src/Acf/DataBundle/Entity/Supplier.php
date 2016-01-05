<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @author sasedev
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\SupplierRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label", "company"}, errorPath="label", groups={"label"})
 *         @UniqueEntity(fields={"number", "company"}, errorPath="number", groups={"number"})
 *         @UniqueEntity(fields={"fisc", "company"}, errorPath="fisc", groups={"fisc"})
 */
class Supplier extends Relation
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
}
