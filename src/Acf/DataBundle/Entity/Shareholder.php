<?php
namespace Acf\DataBundle\Entity;

/**
 * Shareholder
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Shareholder
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var Company
     */
    protected $company;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $cin;

    /**
     *
     * @var string
     */
    protected $quality;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var integer
     */
    protected $trades;

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
        $this->trades = 0;
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
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return Shareholder
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Shareholder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     *
     * @param string $cin
     *
     * @return Shareholder
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     *
     * @param string $quality
     *
     * @return Shareholder
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *
     * @param string $address
     *
     * @return Shareholder
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get trades
     *
     * @return integer
     */
    public function getTrades()
    {
        return $this->trades;
    }

    /**
     * Set trades
     *
     * @param integer $trades
     *
     * @return Shareholder
     */
    public function setTrades($trades)
    {
        $this->trades = $trades;

        return $this;
    }

    /**
     * Get dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Shareholder
     */
    public function setDtCrea($dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Shareholder
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     *
     * @return number
     */
    public function getTradesv()
    {
        return \floatval($this->trades * $this->getCompany()->getActionvn());
    }

    /**
     *
     * @return number
     */
    public function getTradesp()
    {
        return \floatval(100 * $this->trades / $this->getCompany()->getActioncount());
    }

    /**
     */
    public function __clone()
    {
    }
}
