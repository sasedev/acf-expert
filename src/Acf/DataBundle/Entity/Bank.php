<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Bank
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BankRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label", "company"}, errorPath="label", groups={"label"})
 *         @UniqueEntity(fields={"number", "company"}, errorPath="number", groups={"number"})
 */
class Bank extends Account
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
}
