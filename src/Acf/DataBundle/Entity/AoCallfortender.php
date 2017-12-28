<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AoCallfortender
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="ao_callfortenders")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AoCallfortenderRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 */
class AoCallfortender
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
     * @var string
     */
    const AVIS_ADC = "ADC";

    /**
     *
     * @var string
     */
    const AVIS_AAO = "AAO";

    /**
     *
     * @var string
     */
    const AVIS_ADR = "ADR";

    /**
     *
     * @var string
     */
    const AVIS_ADAN = "ADAN";

    /**
     *
     * @var string
     */
    const AVIS_ADAMI = "ADAMI";

    /**
     *
     * @var string
     */
    const AVIS_ADCONC = "ADCONC";

    /**
     *
     * @var string
     */
    const AVIS_ADA = "ADA";

    /**
     *
     * @var string
     */
    const AVIS_AR = "AR";

    /**
     *
     * @var string
     */
    const AVIS_AA = "AA";

    /**
     *
     * @var string
     */
    const AVIS_ADCAND = "ADCAND";

    /**
     *
     * @var string
     */
    const AVIS_AD = "AD";

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
     * @var AoSubCateg @ORM\ManyToOne(targetEntity="AoSubCateg", inversedBy="callfortenders", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="categ_id", referencedColumnName="id")
     *      })
     */
    protected $grp;

    /**
     *
     * @var integer @ORM\Column(name="ref", type="text", nullable=false)
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
     * @var string @ORM\Column(name="typeavis", type="text", nullable=true)
     *      @Assert\Choice(callback="choiceTypeAvisCallback", groups={"typeAvis"})
     */
    protected $typeAvis;

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
     * @return AoCallfortender
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get $grp
     *
     * @return AoSubCateg
     */
    public function getGrp()
    {
        return $this->grp;
    }

    /**
     * Set $grp
     *
     * @param AoSubCateg $grp
     *
     * @return AoCallfortender
     */
    public function setGrp(AoSubCateg $grp)
    {
        $this->grp = $grp;

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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get $typeAvis
     *
     * @return string
     */
    public function getTypeAvis()
    {
        return $this->typeAvis;
    }

    /**
     * Set $typeAvis
     *
     * @param string $typeAvis
     *
     * @return AoCallfortender
     */
    public function setTypeAvis($typeAvis)
    {
        $this->typeAvis = $typeAvis;

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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
     * @return AoCallfortender
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
            'AoCallfortender.nature.choice.' . self::NATURE_NAT => self::NATURE_NAT,
            'AoCallfortender.nature.choice.' . self::NATURE_INTER => self::NATURE_INTER
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
     * Choice Form typeAvis
     *
     * @return multitype:string
     */
    public static function choiceTypeAvis()
    {
        return array(
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADC => self::AVIS_ADC,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_AAO => self::AVIS_AAO,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADR => self::AVIS_ADR,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADAN => self::AVIS_ADAN,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADAMI => self::AVIS_ADAMI,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADCONC => self::AVIS_ADCONC,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADA => self::AVIS_ADA,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_AR => self::AVIS_AR,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_AA => self::AVIS_AA,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_ADCAND => self::AVIS_ADCAND,
            'AoCallfortender.typeAvis.choice.' . self::AVIS_AD => self::AVIS_AD
        );
    }

    /**
     * Choice Validator typeAvis
     *
     * @return multitype:string
     */
    public static function choiceTypeAvisCallback()
    {
        return array(
            self::AVIS_ADC,
            self::AVIS_AAO,
            self::AVIS_ADR,
            self::AVIS_ADAN,
            self::AVIS_ADAMI,
            self::AVIS_ADCONC,
            self::AVIS_ADA,
            self::AVIS_AR,
            self::AVIS_AA,
            self::AVIS_ADCAND,
            self::AVIS_AD
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
            'AoCallfortender.status.choice.' . self::STATUS_SHOW => self::STATUS_SHOW,
            'AoCallfortender.status.choice.' . self::STATUS_HIDE => self::STATUS_HIDE
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
