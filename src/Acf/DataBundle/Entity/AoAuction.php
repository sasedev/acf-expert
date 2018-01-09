<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AoAuction
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="ao_auctions")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AoAuctionRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 */
class AoAuction
{

    /**
     *
     * @var string
     */
    const NATURE_NAT = "Nat";

    /**
     *
     * @var string
     */
    const NATURE_INTER = "Inter";

    /**
     *
     * @var integer
     */
    const STATUS_SHOW = 1;

    /**
     *
     * @var integer
     */
    const STATUS_HIDE = 2;

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var integer @ORM\Column(name="ref", type="integer", nullable=false)
     */
    protected $ref;

    /**
     *
     * @var string @ORM\Column(name="img", type="text", nullable=true)
     *      @Assert\Image(maxSize="20480k", groups={"img"})
     */
    protected $img;

    /**
     *
     * @var \DateTime @ORM\Column(name="dtpub", type="date", nullable=true)
     */
    protected $dtPublication;

    /**
     *
     * @var string @ORM\Column(name="country", type="text", nullable=true)
     */
    protected $country;

    /**
     *
     * @var string @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var string @ORM\Column(name="company", type="text", nullable=true)
     */
    protected $company;

    /**
     *
     * @var string @ORM\Column(name="nature", type="text", nullable=true)
     *      @Assert\Choice(callback="choiceNatureCallback", groups={"nature"})
     */
    protected $nature;

    /**
     *
     * @var \DateTime @ORM\Column(name="dtend", type="date", nullable=true)
     */
    protected $dtEnd;

    /**
     *
     * @var \DateTime @ORM\Column(name="dtopen", type="date", nullable=true)
     */
    protected $dtOpen;

    /**
     *
     * @var string @ORM\Column(name="adress", type="text", nullable=true)
     */
    protected $adress;

    /**
     *
     * @var string @ORM\Column(name="price", type="text", nullable=true)
     */
    protected $price;

    /**
     *
     * @var string @ORM\Column(name="adref", type="text", nullable=true)
     */
    protected $addRef;

    /**
     *
     * @var string @ORM\Column(name="source", type="text", nullable=true)
     */
    protected $source;

    /**
     *
     * @var integer @ORM\Column(name="status", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceStatusCallback", groups={"status"})
     */
    protected $status;

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
     *
     * @param integer $value
     * @param integer $count
     */
    public function __construct($value = 1, $count = 0)
    {
        $this->dtCrea = new \DateTime('now');
    }

    /**
     * Get $id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set $ref
     *
     * @param string $ref
     *
     * @return AoAuction
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get $img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set $img
     *
     * @param string $img
     *
     * @return AoAuction
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get $dtPublication
     *
     * @return \DateTime
     */
    public function getDtPublication()
    {
        return $this->dtPublication;
    }

    /**
     * Set $dtPublication
     *
     * @param \DateTime $dtPublication
     *
     * @return AoAuction
     */
    public function setDtPublication($dtPublication)
    {
        $this->dtPublication = $dtPublication;

        return $this;
    }

    /**
     * Get $country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set $country
     *
     * @param string $country
     *
     * @return AoAuction
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get $description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set $description
     *
     * @param string $description
     *
     * @return AoAuction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get $company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set $company
     *
     * @param string $company
     *
     * @return AoAuction
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get $nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set $nature
     *
     * @param string $nature
     *
     * @return AoAuction
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get $dtEnd
     *
     * @return \DateTime
     */
    public function getDtEnd()
    {
        return $this->dtEnd;
    }

    /**
     * Set $dtEnd
     *
     * @param \DateTime $dtEnd
     *
     * @return AoAuction
     */
    public function setDtEnd($dtEnd)
    {
        $this->dtEnd = $dtEnd;

        return $this;
    }

    /**
     * Get $dtOpen
     *
     * @return \DateTime
     */
    public function getDtOpen()
    {
        return $this->dtOpen;
    }

    /**
     * Set $dtOpen
     *
     * @param \DateTime $dtOpen
     *
     * @return AoAuction
     */
    public function setDtOpen($dtOpen)
    {
        $this->dtOpen = $dtOpen;

        return $this;
    }

    /**
     * Get $adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set $adress
     *
     * @param string $adress
     *
     * @return AoAuction
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get $price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set $price
     *
     * @param string $price
     *
     * @return AoAuction
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get $addRef
     *
     * @return string
     */
    public function getAddRef()
    {
        return $this->addRef;
    }

    /**
     * Set $addRef
     *
     * @param string $addRef
     *
     * @return AoAuction
     */
    public function setAddRef($addRef)
    {
        $this->addRef = $addRef;

        return $this;
    }

    /**
     * Get $source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set $source
     *
     * @param string $source
     *
     * @return AoAuction
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get $status
     *
     * @return number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set $status
     *
     * @param number $status
     *
     * @return AoAuction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get $dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set $dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return AoAuction
     */
    public function setDtCrea($dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get $dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set $dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return AoAuction
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Choice Form nature
     *
     * @return multitype:string
     */
    public static function choiceNaturetype()
    {
        return array(
            'AoAuction.nature.choice.' . self::NATURE_NAT => self::NATURE_NAT,
            'AoAuction.nature.choice.' . self::NATURE_INTER => self::NATURE_INTER
        );
    }

    /**
     * Choice Validator nature
     *
     * @return multitype:string
     */
    public static function choiceNatureCallback()
    {
        return array(
            self::NATURE_NAT,
            self::NATURE_INTER
        );
    }

    /**
     * Choice Form status
     *
     * @return multitype:string
     */
    public static function choiceStatus()
    {
        return array(
            'AoAuction.status.choice.' . self::STATUS_SHOW => self::STATUS_SHOW,
            'AoAuction.status.choice.' . self::STATUS_HIDE => self::STATUS_HIDE
        );
    }

    /**
     * Choice Validator status
     *
     * @return multitype:integer
     */
    public static function choiceStatusCallback()
    {
        return array(
            self::STATUS_SHOW,
            self::STATUS_HIDE
        );
    }
}
