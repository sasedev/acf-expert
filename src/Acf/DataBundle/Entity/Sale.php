<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sale
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\SaleRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"bill", "monthlyBalance"}, errorPath="bill", groups={"bill"})
 */
class Sale extends Transaction
{

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="SecondaryVat", mappedBy="sale", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"vatInfo" = "ASC"})
     */
    protected $secondaryVats;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->secondaryVats = new ArrayCollection();
    }

    /**
     * Add secondaryVat
     *
     * @param SecondaryVat $secondaryVat
     *
     * @return Sale
     */
    public function addSecondaryVat(SecondaryVat $secondaryVat)
    {
        $this->secondaryVats[] = $secondaryVat;

        return $this;
    }

    /**
     * Remove secondaryVat
     *
     * @param SecondaryVat $secondaryVat
     *
     * @return Sale
     */
    public function removeSecondaryVat(SecondaryVat $secondaryVat)
    {
        $this->secondaryVats->removeElement($secondaryVat);

        return $this;
    }

    /**
     * Get secondaryVats
     *
     * @return ArrayCollection
     */
    public function getSecondaryVats()
    {
        return $this->secondaryVats;
    }

    /**
     *
     * @param Collection $secondaryVats
     *
     * @return Sale
     */
    public function setSecondaryVats(Collection $secondaryVats)
    {
        $this->secondaryVats = $secondaryVats;

        return $this;
    }

    /**
     * Choice Form regime
     *
     * @return multitype:string
     */
    public static function choiceRegime()
    {
        return array(
            'Sale.regime.choice.' . self::R_0 => self::R_0,
            'Sale.regime.choice.' . self::R_1 => self::R_1,
            'Sale.regime.choice.' . self::R_2 => self::R_2,
            'Sale.regime.choice.' . self::R_3 => self::R_3
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
            self::R_3
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
            $secondaryVats = $this->getSecondaryVats();
            $this->secondaryVats = new ArrayCollection();
            foreach ($secondaryVats as $sector) {
                $cloneSector = clone $sector;
                $this->secondaryVats->add($cloneSector);
            }
        }
    }
}
