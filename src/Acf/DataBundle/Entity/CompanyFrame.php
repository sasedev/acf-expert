<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sasedev\Commons\SharedBundle\Validator as ExtraAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CompanyFrame
 * @ORM\Table(name="acf_company_frames")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\CompanyFrameRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CompanyFrame
{

    /**
     *
     * @var integer
     */
    const SEXE_MISS = 1;

    /**
     *
     * @var integer
     */
    const SEXE_MRS = 2;

    /**
     *
     * @var integer
     */
    const SEXE_MR = 3;

    /**
     *
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="companyFrames", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var Job @ORM\ManyToOne(targetEntity="Job", inversedBy="companyFrames", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     *      })
     */
    protected $job;

    /**
     *
     * @var string @ORM\Column(name="lastname", type="text", nullable=true)
     */
    protected $lastName;

    /**
     *
     * @var string @ORM\Column(name="firstname", type="text", nullable=true)
     */
    protected $firstName;

    /**
     *
     * @var integer @ORM\Column(name="sexe", type="bigint", nullable=true)
     */
    protected $sexe;

    /**
     *
     * @var string @ORM\Column(name="cin", type="text", nullable=true)
     */
    protected $cin;

    /**
     *
     * @var string @ORM\Column(name="passport", type="text", nullable=true)
     */
    protected $passport;

    /**
     *
     * @var string @ORM\Column(name="strnum", type="text", nullable=true)
     *      @Assert\Length(max="15", groups={"streetNum"})
     */
    protected $streetNum;

    /**
     *
     * @var string @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     *
     * @var string @ORM\Column(name="address2", type="text", nullable=true)
     */
    protected $address2;

    /**
     *
     * @var string @ORM\Column(name="town", type="text", nullable=true)
     *      @Assert\Length(max="120", groups={"town"})
     */
    protected $town;

    /**
     *
     * @var string @ORM\Column(name="zipcode", type="text", nullable=true)
     *      @Assert\Length(max="15", groups={"zipCode"})
     */
    protected $zipCode;

    /**
     *
     * @var string @ORM\Column(name="country", type="text", nullable=true)
     *      @Assert\Country(groups={"country"})
     */
    protected $country;

    /**
     *
     * @var string @ORM\Column(name="phone", type="text", nullable=true)
     *      @ExtraAssert\Phone(groups={"phone"})
     */
    protected $phone;

    /**
     *
     * @var string @ORM\Column(name="mobile", type="text", nullable=true)
     *      @ExtraAssert\Phone(groups={"mobile"})
     */
    protected $mobile;

    /**
     *
     * @var string @ORM\Column(name="email", type="text", nullable=true)
     *      @Assert\Email(checkMX=true, checkHost=true, groups={"email"})
     */
    protected $email;

    /**
     *
     * @var string @ORM\Column(name="others", type="text", nullable=true)
     */
    protected $otherInfos;

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
     * @return CompanyFrame
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get job
     *
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job
     *
     * @param Job $job
     *
     * @return CompanyFrame
     */
    public function setJob(Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return CompanyFrame
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return CompanyFrame
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return integer
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set sexe
     *
     * @param integer $sexe
     *
     * @return CompanyFrame
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get string
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set $cin
     *
     * @param string $cin
     *
     * @return CompanyFrame
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get string
     *
     * @return string
     */
    public function getPassport()
    {
        return $this->passport;
    }

    /**
     * Set $passport
     *
     * @param string $passport
     *
     * @return CompanyFrame
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * Get streetNum
     *
     * @return string
     */
    public function getStreetNum()
    {
        return $this->streetNum;
    }

    /**
     * Set streetNum
     *
     * @param string $streetNum
     *
     * @return CompanyFrame
     */
    public function setStreetNum($streetNum)
    {
        $this->streetNum = $streetNum;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return CompanyFrame
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return CompanyFrame
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return CompanyFrame
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return CompanyFrame
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return CompanyFrame
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return CompanyFrame
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return CompanyFrame
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return CompanyFrame
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get otherInfos
     *
     * @return string
     */
    public function getOtherInfos()
    {
        return $this->otherInfos;
    }

    /**
     * Set otherInfos
     *
     * @param string $otherInfos
     *
     * @return CompanyFrame
     */
    public function setOtherInfos($otherInfos)
    {
        $this->otherInfos = $otherInfos;

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
     * @return CompanyFrame
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
     * @return CompanyFrame
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get calculated fullName From username, firstName and lastName
     *
     * @return string
     */
    public function getFullName()
    {
        if (null == $this->getFirstName() && null == $this->getLastName()) {
            return $this->getUsername();
        } elseif (null != $this->getFirstName() && null != $this->getLastName()) {
            return $this->getFirstName() . ' ' . $this->getLastName();
        } else {
            if (null != $this->getLastName()) {
                return $this->getLastName();
            }
            if (null != $this->getFirstName()) {
                return $this->getFirstName();
            }
        }
    }

    /**
     * Choice Form sexe
     *
     * @return multitype:string
     */
    public static function choiceSexe()
    {
        return array(
            'CompanyFrame.sexe.choice.' . self::SEXE_MISS => self::SEXE_MISS,
            'CompanyFrame.sexe.choice.' . self::SEXE_MRS => self::SEXE_MRS,
            'CompanyFrame.sexe.choice.' . self::SEXE_MR => self::SEXE_MR
        );
    }

    /**
     * Choice Validator sexe
     *
     * @return multitype:integer
     */
    public static function choiceSexeCallback()
    {
        return array(
            self::SEXE_MISS,
            self::SEXE_MRS,
            self::SEXE_MR
        );
    }

    /**
     */
    public function __clone()
    {}
}
