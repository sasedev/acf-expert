<?php
namespace Acf\DataBundle\Entity;

/**
 * CompanyFrame
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
     * @var Job
     */
    protected $job;

    /**
     *
     * @var string
     */
    protected $lastName;

    /**
     *
     * @var string
     */
    protected $firstName;

    /**
     *
     * @var integer
     */
    protected $sexe;

    /**
     *
     * @var string
     */
    protected $cin;

    /**
     *
     * @var string
     */
    protected $passport;

    /**
     *
     * @var string
     */
    protected $streetNum;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var string
     */
    protected $address2;

    /**
     *
     * @var string
     */
    protected $town;

    /**
     *
     * @var string
     */
    protected $zipCode;

    /**
     *
     * @var string
     */
    protected $country;

    /**
     *
     * @var string
     */
    protected $phone;

    /**
     *
     * @var string
     */
    protected $mobile;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $otherInfos;

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
    {
    }
}
