<?php
namespace Acf\DataBundle\Entity;

/**
 * SecondaryVat
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SecondaryVat
{

    /**
     *
     * @var integer
     */
    const VI_0 = 0;

    /**
     *
     * @var integer
     */
    const VI_6 = 6;

    /**
     *
     * @var integer
     */
    const VI_12 = 12;

    /**
     *
     * @var integer
     */
    const VI_18 = 18;

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var Sale
     */
    protected $sale;

    /**
     *
     * @var integer
     */
    protected $vatInfo;

    /**
     *
     * @var float
     */
    protected $vat;

    /**
     *
     * @var float
     */
    protected $balanceNet;

    /**
     *
     * @var float
     */
    protected $balanceTtc;

    /**
     *
     * @var \DateTime
     */
    protected $dtCrea;

    /**
     *
     * @var \DateTime
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
        $this->vatInfo = self::VI_0;
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
     * @return integer
     */
    public function getVatInfo()
    {
        return $this->vatInfo;
    }

    /**
     *
     * @param integer $vatInfo
     *
     * @return SecondaryVat
     */
    public function setVatInfo($vatInfo)
    {
        $this->vatInfo = $vatInfo;

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
     * Choice Form vatInfo
     *
     * @return multitype:string
     */
    public static function choiceVatInfo()
    {
        return array(
            'SecondaryVat.vatInfo.choice.' . self::VI_0 => self::VI_0,
            'SecondaryVat.vatInfo.choice.' . self::VI_6 => self::VI_6,
            'SecondaryVat.vatInfo.choice.' . self::VI_12 => self::VI_12,
            'SecondaryVat.vatInfo.choice.' . self::VI_18 => self::VI_18
        );
    }

    /**
     * Choice Validator vatInfo
     *
     * @return multitype:integer
     */
    public static function choiceVatInfoCallback()
    {
        return array(
            self::VI_0,
            self::VI_6,
            self::VI_12,
            self::VI_18
        );
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
    {
    }
}
