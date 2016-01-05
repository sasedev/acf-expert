<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @author sasedev
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\FundRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label", "company"}, errorPath="label", groups={"label"})
 *         @UniqueEntity(fields={"number", "company"}, errorPath="number", groups={"number"})
 */
class Fund extends Account
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
}
