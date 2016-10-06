<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Shareholder
 * @ORM\Table(name="acf_company_shareholders")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\ShareholderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Shareholder
{

    /**
     *
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="shareholders", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var string @ORM\Column(name="name", type="text", nullable=false)
     */
    protected $name;

    /**
     *
     * @var string @ORM\Column(name="cin", type="text", nullable=false)
     */
    protected $cin;

    /**
     *
     * @var string @ORM\Column(name="quality", type="text", nullable=false)
     */
    protected $quality;

    /**
     *
     * @var string @ORM\Column(name="address", type="text", nullable=false)
     */
    protected $address;

    /**
     *
     * @var integer @ORM\Column(name="trades", type="bigint", nullable=false)
     */
    protected $trades;

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
        $this->trades = 0;
    }

    /**
     * Get id
     *
     * @return guid
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
    {}
}
