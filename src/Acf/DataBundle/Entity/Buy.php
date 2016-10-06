<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BuyRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class Buy extends Transaction
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Choice Form regime
     *
     * @return multitype:string
     */
    public static function choiceRegime()
    {
        return array(
            'Buy.regime.choice.' . self::R_0 => self::R_0,
            'Buy.regime.choice.' . self::R_1 => self::R_1,
            'Buy.regime.choice.' . self::R_2 => self::R_2,
            'Buy.regime.choice.' . self::R_3 => self::R_3,
            'Buy.regime.choice.' . self::R_4 => self::R_4,
            'Buy.regime.choice.' . self::R_5 => self::R_5
        );
    }

    /**
     * Choice Validator regime
     *
     * @return multitype:integer
     */
    public static function choiceRegimeCallback()
    {
        return array(
            self::R_0,
            self::R_1,
            self::R_2,
            self::R_3,
            self::R_4,
            self::R_5
        );
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Acf\DataBundle\Entity\Transaction::__clone()
     */
    public function __clone()
    {
        if ($this->id) {
            $docs = $this->getDocs();
            $this->docs = new ArrayCollection();
            foreach ($docs as $doc) {
                $cloneDoc = clone $doc;
                $this->docs->add($cloneDoc);
            }
        }
    }
}
