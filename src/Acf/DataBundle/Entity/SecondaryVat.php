<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SecondaryVat
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_transaction_vats")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\SecondaryVatRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class SecondaryVat
{

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Sale @ORM\ManyToOne(targetEntity="Sale", inversedBy="secondaryVats", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     *      })
     */
    protected $sale;

    /**
     *
     * @var string @ORM\Column(name="vatinfo", type="text", nullable=false)
     */
    protected $vatInfo;

    /**
     *
     * @var float @ORM\Column(name="vat", type="float", precision=10, scale=0, nullable=false)
     *      @Assert\GreaterThanOrEqual(value=0, groups={"vat"})
     */
    protected $vat;

    /**
     *
     * @var float @ORM\Column(name="balance_net", type="float", precision=10, scale=0, nullable=false)
     *      @Assert\GreaterThan(value=0, groups={"balanceNet"})
     */
    protected $balanceNet;

    /**
     *
     * @var float @ORM\Column(name="balance_ttc", type="float", precision=10, scale=0, nullable=false)
     *      @Assert\GreaterThan(value=0, groups={"balanceTtc"})
     */
    protected $balanceTtc;

    /**
     *
     * @var \DateTime @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     */
    protected $dtCrea;

    /**
     *
     * @var \DateTime @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     *      @Gedmo\Timestampable(on="update")
     */
    protected $dtUpdate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->vat = 0;
        $this->balanceTtc = 0;
        $this->balanceNet = 0;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return Sale
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     *
     * @param Sale $sale
     *
     * @return SecondaryVat
     */
    public function setSale(Sale $sale)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     *
     * @return MonthlyBalance
     */
    public function getMonthlyBalance()
    {
        return $this->getSale()->getMonthlyBalance();
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->getSale()->getCompany();
    }

    /**
     *
     * @return string
     */
    public function getVatInfo()
    {
        return $this->vatInfo;
    }

    /**
     *
     * @param string $vatInfo
     *
     * @return SecondaryVat
     */
    public function setVatInfo($vatInfo)
    {
        if ($vatInfo instanceof Vat) {
            $this->vatInfo = $vatInfo->getTitle();
        } else {
            $this->vatInfo = $vatInfo;
        }

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     *
     * @param float $vat
     *
     * @return SecondaryVat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getBalanceNet()
    {
        return $this->balanceNet;
    }

    /**
     *
     * @param float $balanceNet
     *
     * @return SecondaryVat
     */
    public function setBalanceNet($balanceNet)
    {
        $this->balanceNet = $balanceNet;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getBalanceTtc()
    {
        return $this->balanceTtc;
    }

    /**
     *
     * @param float $balanceTtc
     *
     * @return SecondaryVat
     */
    public function setBalanceTtc($balanceTtc)
    {
        $this->balanceTtc = $balanceTtc;

        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     *
     * @param \DateTime $dtCrea
     *
     * @return SecondaryVat
     */
    public function setDtCrea(\DateTime $dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     *
     * @param \DateTime $dtUpdate
     *
     * @return SecondaryVat
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getSale()->getLabel() . ' ' . $this->getVatInfo();
    }

    /**
     */
    public function __clone()
    {}
}
