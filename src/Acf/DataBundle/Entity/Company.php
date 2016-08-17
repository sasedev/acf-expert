<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Sasedev\Commons\SharedBundle\Validator as ExtraAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 * @ORM\Table(name="acf_companies")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\CompanyRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 * @UniqueEntity(fields={"corporateName"}, errorPath="corporateName", groups={"corporateName"})
 */
class Company
{

    /**
     *
     * @var integer
     */
    const PHTYPE_MORAL = 1;

    /**
     *
     * @var integer
     */
    const PHTYPE_PHYSIC = 2;

    /**
     *
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="ref", type="text", nullable=false)
     */
    protected $ref;

    /**
     *
     * @var string @ORM\Column(name="corporate_name", type="text", nullable=false)
     */
    protected $corporateName;

    /**
     *
     * @var CompanyType @ORM\ManyToOne(targetEntity="CompanyType", inversedBy="companies", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     *      })
     */
    protected $type;

    /**
     *
     * @var string @ORM\Column(name="tribunal", type="text", nullable=true)
     */
    protected $tribunal;

    /**
     *
     * @var string @ORM\Column(name="fisc", type="text", nullable=true)
     */
    protected $fisc;

    /**
     *
     * @var string @ORM\Column(name="cnss", type="text", nullable=true)
     */
    protected $cnss;

    /**
     *
     * @var string @ORM\Column(name="bureaucnss", type="text", nullable=true)
     */
    protected $cnssBureau;

    /**
     *
     * @var integer @ORM\Column(name="physicaltype", type="bigint", nullable=false)
     *      @Assert\Choice(callback="choicePhysicaltypeCallback", groups={"physicaltype"})
     */
    protected $physicaltype;

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
     * @var string @ORM\Column(name="commercial_register", type="text", nullable=true)
     */
    protected $commercialRegister;

    /**
     *
     * @var string @ORM\Column(name="bureaurc", type="text", nullable=true)
     */
    protected $commercialRegisterBureau;

    /**
     *
     * @var string @ORM\Column(name="customs_code", type="text", nullable=true)
     */
    protected $customsCode;

    /**
     *
     * @var float @ORM\Column(name="actionvn", type="float", precision=10, scale=0, nullable=false)
     *      @Assert\GreaterThan(value="0", groups={"actionvn"})
     */
    protected $actionvn;

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
     * @var string @ORM\Column(name="fax", type="text", nullable=true)
     *      @ExtraAssert\Phone(groups={"phone"})
     */
    protected $fax;

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
     *
     * @var Collection @ORM\OneToMany(targetEntity="Stock", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"year" = "ASC"})
     */
    protected $stocks;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Phone", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $phones;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Address", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $addresses;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyFrame", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"lastName" = "ASC", "firstName" = "ASC"})
     */
    protected $companyFrames;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyLabel", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"name" = "ASC"})
     */
    protected $companyLabels;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Shareholder", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"name" = "ASC"})
     */
    protected $shareholders;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Pilot", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"mission" = "ASC"})
     */
    protected $pilots;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyNature", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $companyNatures;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Relation", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $relations;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Sector", inversedBy="companies", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_company_sectors",
     *      joinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $sectors;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="User", inversedBy="companies", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_company_users",
     *      joinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $users;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyUser", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $companyUsers;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="User", inversedBy="admCompanies", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_company_admins",
     *      joinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $admins;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyAdmin", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $companyAdmins;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Account", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $accounts;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Withholding", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $withholdings;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="MonthlyBalance", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"year" = "ASC", "month" = "ASC"})
     */
    protected $monthlyBalances;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="MPaye", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"year" = "ASC", "month" = "ASC"})
     */
    protected $payes;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroup", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroups;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupcomptable", mappedBy="company", cascade={"persist",
     *      "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroupcomptables;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupfiscal", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroupfiscals;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupperso", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgrouppersos;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupsyst", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroupsysts;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupbank", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroupbanks;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupaudit", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $docgroupaudits;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Doc", mappedBy="company", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "DESC"})
     */
    protected $docs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->actionvn = 0;
        $this->physicaltype = self::PHTYPE_MORAL;
        $this->phones = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->companyFrames = new ArrayCollection();
        $this->companyLabels = new ArrayCollection();
        $this->shareholders = new ArrayCollection();
        $this->pilots = new ArrayCollection();
        $this->companyNatures = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->sectors = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->companyUsers = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->companyAdmins = new ArrayCollection();
        $this->accounts = new ArrayCollection();
        $this->withholdings = new ArrayCollection();
        $this->monthlyBalances = new ArrayCollection();
        $this->payes = new ArrayCollection();
        $this->docgroups = new ArrayCollection();
        $this->docgroupcomptables = new ArrayCollection();
        $this->docgroupfiscals = new ArrayCollection();
        $this->docgrouppersos = new ArrayCollection();
        $this->docgroupsysts = new ArrayCollection();
        $this->docgroupbanks = new ArrayCollection();
        $this->docgroupaudits = new ArrayCollection();
        $this->docs = new ArrayCollection();
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
     * Get id
     *
     * @return string
     */
    public function getIdn()
    {
        return \str_ireplace('-', '', $this->id);
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Company
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get corporateName
     *
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * Set corporateName
     *
     * @param string $corporateName
     *
     * @return Company
     */
    public function setCorporateName($corporateName)
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    /**
     * Get type
     *
     * @return CompanyType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param CompanyType $type
     *
     * @return Company
     */
    public function setType(CompanyType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTribunal()
    {
        return $this->tribunal;
    }

    /**
     *
     * @param string $tribunal
     *
     * @return Company
     */
    public function setTribunal($tribunal)
    {
        $this->tribunal = $tribunal;

        return $this;
    }

    /**
     * Get fisc
     *
     * @return string
     */
    public function getFisc()
    {
        return $this->fisc;
    }

    /**
     * Set fisc
     *
     * @param string $fisc
     *
     * @return Company
     */
    public function setFisc($fisc)
    {
        $this->fisc = $fisc;

        return $this;
    }

    /**
     * Get cnss
     *
     * @return string
     */
    public function getCnss()
    {
        return $this->cnss;
    }

    /**
     * Set cnss
     *
     * @param string $cnss
     *
     * @return Company
     */
    public function setCnss($cnss)
    {
        $this->cnss = $cnss;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCnssBureau()
    {
        return $this->cnssBureau;
    }

    /**
     *
     * @param string $cnssBureau
     *
     * @return Company
     */
    public function setCnssBureau($cnssBureau)
    {
        $this->cnssBureau = $cnssBureau;

        return $this;
    }

    /**
     * Get integer
     *
     * @return integer
     */
    public function getPhysicaltype()
    {
        return $this->physicaltype;
    }

    /**
     * Set $physicaltype
     *
     * @param integer $physicaltype
     *
     * @return Company $this
     */
    public function setPhysicaltype($physicaltype)
    {
        $this->physicaltype = $physicaltype;

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
     * @return Company $this
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
     * @return Company $this
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * Get commercialRegister
     *
     * @return string
     */
    public function getCommercialRegister()
    {
        return $this->commercialRegister;
    }

    /**
     * Set commercialRegister
     *
     * @param string $commercialRegister
     *
     * @return Company
     */
    public function setCommercialRegister($commercialRegister)
    {
        $this->commercialRegister = $commercialRegister;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCommercialRegisterBureau()
    {
        return $this->commercialRegisterBureau;
    }

    /**
     *
     * @param string $commercialRegisterBureau
     *
     * @return Company
     */
    public function setCommercialRegisterBureau($commercialRegisterBureau)
    {
        $this->commercialRegisterBureau = $commercialRegisterBureau;

        return $this;
    }

    /**
     * Get customsCode
     *
     * @return string
     */
    public function getCustomsCode()
    {
        return $this->customsCode;
    }

    /**
     * Set customsCode
     *
     * @param string $customsCode
     *
     * @return Company
     */
    public function setCustomsCode($customsCode)
    {
        $this->customsCode = $customsCode;

        return $this;
    }

    /**
     * Get actionvn
     *
     * @return float
     */
    public function getActionvn()
    {
        return $this->actionvn;
    }

    /**
     * Set actionvn
     *
     * @param float $actionvn
     *
     * @return Company
     */
    public function setActionvn($actionvn)
    {
        $this->actionvn = $actionvn;

        return $this;
    }

    /**
     * Get actioncount
     *
     * @return float
     */
    public function getActioncount()
    {
        $actions = 0;
        foreach ($this->shareholders as $sharedholder) {
            $actions += $sharedholder->getTrades();
        }

        return $actions;
    }

    /**
     * Get capital
     *
     * @return float
     */
    public function getCapital()
    {
        $capital = 0;
        foreach ($this->shareholders as $sharedholder) {
            $capital += $sharedholder->getTrades() * $this->getActionvn();
        }

        return $capital;
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Company
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add stock
     *
     * @param Stock $stock
     *
     * @return Company
     */
    public function addStock(Stock $stock)
    {
        $this->stocks[] = $stock;

        return $this;
    }

    /**
     * Remove stock
     *
     * @param Stock $stock
     *
     * @return Company
     */
    public function removeStock(Stock $stock)
    {
        $this->stocks->removeElement($stock);

        return $this;
    }

    /**
     * Get stocks
     *
     * @return ArrayCollection
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     *
     * @param Collection $stocks
     *
     * @return Company
     */
    public function setStocks(Collection $stocks)
    {
        $this->stocks = $stocks;

        return $this;
    }

    /**
     * Add phone
     *
     * @param Phone $phone
     *
     * @return Company
     */
    public function addPhone(Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param Phone $phone
     *
     * @return Company
     */
    public function removePhone(Phone $phone)
    {
        $this->phones->removeElement($phone);

        return $this;
    }

    /**
     * Get phones
     *
     * @return ArrayCollection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     *
     * @param Collection $phones
     *
     * @return Company
     */
    public function setPhones(Collection $phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Add addresse
     *
     * @param Address $addresse
     *
     * @return Company
     */
    public function addAddress(Address $addresse)
    {
        $this->addresses[] = $addresse;

        return $this;
    }

    /**
     * Remove addresse
     *
     * @param Address $addresse
     *
     * @return Company
     */
    public function removeAddress(Address $addresse)
    {
        $this->addresses->removeElement($addresse);

        return $this;
    }

    /**
     * Get addresses
     *
     * @return ArrayCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     *
     * @param Collection $addresses
     *
     * @return Company
     */
    public function setAddresses(Collection $addresses)
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * Add companyFrame
     *
     * @param CompanyFrame $companyFrame
     *
     * @return Company
     */
    public function addCompanyFrame(CompanyFrame $companyFrame)
    {
        $this->companyFrames[] = $companyFrame;

        return $this;
    }

    /**
     * Remove companyFrame
     *
     * @param CompanyFrame $companyFrame
     *
     * @return Company
     */
    public function removeCompanyFrame(CompanyFrame $companyFrame)
    {
        $this->companyFrames->removeElement($companyFrame);

        return $this;
    }

    /**
     * Get companyFrames
     *
     * @return ArrayCollection
     */
    public function getCompanyFrames()
    {
        return $this->companyFrames;
    }

    /**
     *
     * @param Collection $companyFrames
     *
     * @return Company
     */
    public function setCompanyFrames(Collection $companyFrames)
    {
        $this->companyFrames = $companyFrames;

        return $this;
    }

    /**
     * Add companyLabel
     *
     * @param CompanyLabel $companyLabel
     *
     * @return Company
     */
    public function addCompanyLabel(CompanyLabel $companyLabel)
    {
        $this->companyLabels[] = $companyLabel;

        return $this;
    }

    /**
     * Remove companyLabel
     *
     * @param CompanyLabel $companyLabel
     *
     * @return Company
     */
    public function removeCompanyLabel(CompanyLabel $companyLabel)
    {
        $this->companyLabels->removeElement($companyLabel);

        return $this;
    }

    /**
     * Get companyLabels
     *
     * @return ArrayCollection
     */
    public function getCompanyLabels()
    {
        return $this->companyLabels;
    }

    /**
     *
     * @param Collection $companyLabels
     *
     * @return Company
     */
    public function setCompanyLabels(Collection $companyLabels)
    {
        $this->companyLabels = $companyLabels;

        return $this;
    }

    /**
     * Add shareholder
     *
     * @param Shareholder $shareholder
     *
     * @return Company
     */
    public function addShareholder(Shareholder $shareholder)
    {
        $this->shareholders[] = $shareholder;

        return $this;
    }

    /**
     * Remove shareholder
     *
     * @param Shareholder $shareholder
     *
     * @return Company
     */
    public function removeShareholder(Shareholder $shareholder)
    {
        $this->shareholders->removeElement($shareholder);

        return $this;
    }

    /**
     * Get shareholder
     *
     * @return ArrayCollection
     */
    public function getShareholders()
    {
        return $this->shareholders;
    }

    /**
     *
     * @param Collection $shareholders
     *
     * @return Company
     */
    public function setShareholders(Collection $shareholders)
    {
        $this->shareholders = $shareholders;

        return $this;
    }

    /**
     * Add pilot
     *
     * @param Pilot $pilot
     *
     * @return Company
     */
    public function addPilot(Pilot $pilot)
    {
        $this->pilots[] = $pilot;

        return $this;
    }

    /**
     * Remove pilot
     *
     * @param Pilot $pilot
     *
     * @return Company
     */
    public function removePilot(Pilot $pilot)
    {
        $this->pilots->removeElement($pilot);

        return $this;
    }

    /**
     * Get pilots
     *
     * @return ArrayCollection
     */
    public function getPilots()
    {
        return $this->pilots;
    }

    /**
     *
     * @param Collection $pilots
     *
     * @return Company
     */
    public function setPilots(Collection $pilots)
    {
        $this->pilots = $pilots;

        return $this;
    }

    /**
     * Add companyNature
     *
     * @param CompanyNature $companyNature
     *
     * @return Company
     */
    public function addCompanyNature(CompanyNature $companyNature)
    {
        $this->companyNatures[] = $companyNature;

        return $this;
    }

    /**
     * Remove companyNature
     *
     * @param CompanyNature $companyNature
     *
     * @return Company
     */
    public function removeCompanyNature(CompanyNature $companyNature)
    {
        $this->companyNatures->removeElement($companyNature);

        return $this;
    }

    /**
     * Get companyNatures
     *
     * @return ArrayCollection
     */
    public function getCompanyNatures()
    {
        return $this->companyNatures;
    }

    /**
     *
     * @param Collection $companyNatures
     *
     * @return Company
     */
    public function setCompanyNatures(Collection $companyNatures)
    {
        $this->companyNatures = $companyNatures;

        return $this;
    }

    /**
     * Add relation
     *
     * @param Relation $relation
     *
     * @return Company
     */
    public function addRelation(Relation $relation)
    {
        $this->relations[] = $relation;

        return $this;
    }

    /**
     * Remove relation
     *
     * @param Relation $relation
     *
     * @return Company
     */
    public function removeRelation(Relation $relation)
    {
        $this->relations->removeElement($relation);

        return $this;
    }

    /**
     * Get relations
     *
     * @return ArrayCollection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     *
     * @param Collection $relations
     *
     * @return Company
     */
    public function setRelations(Collection $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Get customers
     *
     * @return ArrayCollection
     */
    public function getCustomers()
    {
        $customers = new ArrayCollection();
        foreach ($this->relations as $relation) {
            if ($relation instanceof Customer) {
                $customers->add($relation);
            }
        }

        return $customers;
    }

    /**
     * Get suppliers
     *
     * @return ArrayCollection
     */
    public function getSuppliers()
    {
        $suppliers = new ArrayCollection();
        foreach ($this->relations as $relation) {
            if ($relation instanceof Supplier) {
                $suppliers->add($relation);
            }
        }

        return $suppliers;
    }

    /**
     * Add sector
     *
     * @param Sector $sector
     *
     * @return Company
     */
    public function addSector(Sector $sector)
    {
        $this->sectors[] = $sector;

        return $this;
    }

    /**
     * Remove sector
     *
     * @param Sector $sector
     *
     * @return Company
     */
    public function removeSector(Sector $sector)
    {
        $this->sectors->removeElement($sector);

        return $this;
    }

    /**
     * Get sectors
     *
     * @return ArrayCollection
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     *
     * @param Collection $sectors
     *
     * @return Company
     */
    public function setSectors(Collection $sectors)
    {
        $this->sectors = $sectors;

        return $this;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Company
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     *
     * @return Company
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     *
     * @param Collection $users
     *
     * @return Company
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Add companyUser
     *
     * @param CompanyUser $companyUser
     *
     * @return Company
     */
    public function addCompanyUser(CompanyUser $companyUser)
    {
        $this->companyUsers[] = $companyUser;

        return $this;
    }

    /**
     * Remove companyUser
     *
     * @param CompanyUser $companyUser
     *
     * @return Company
     */
    public function removeCompanyUser(CompanyUser $companyUser)
    {
        $this->companyUsers->removeElement($companyUser);

        return $this;
    }

    /**
     * Get companyUsers
     *
     * @return ArrayCollection
     */
    public function getCompanyUsers()
    {
        return $this->companyUsers;
    }

    /**
     *
     * @param Collection $companyUsers
     *
     * @return Company
     */
    public function setCompanyUsers(Collection $companyUsers)
    {
        $this->companyUsers = $companyUsers;

        return $this;
    }

    /**
     * Add admin
     *
     * @param User $admin
     *
     * @return Company
     */
    public function addAdmin(User $admin)
    {
        $this->admins[] = $admin;

        return $this;
    }

    /**
     * Remove admin
     *
     * @param User $admin
     *
     * @return Company
     */
    public function removeAdmin(User $admin)
    {
        $this->admins->removeElement($admin);

        return $this;
    }

    /**
     * Get admins
     *
     * @return ArrayCollection
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     *
     * @param Collection $admins
     *
     * @return Company
     */
    public function setAdmins(Collection $admins)
    {
        $this->admins = $admins;

        return $this;
    }

    /**
     * Add companyAdmin
     *
     * @param CompanyAdmin $companyAdmin
     *
     * @return Company
     */
    public function addCompanyAdmin(CompanyAdmin $companyAdmin)
    {
        $this->companyAdmins[] = $companyAdmin;

        return $this;
    }

    /**
     * Remove companyAdmin
     *
     * @param CompanyAdmin $companyAdmin
     *
     * @return Company
     */
    public function removeCompanyAdmin(CompanyAdmin $companyAdmin)
    {
        $this->companyAdmins->removeElement($companyAdmin);

        return $this;
    }

    /**
     * Get companyAdmins
     *
     * @return ArrayCollection
     */
    public function getCompanyAdmins()
    {
        return $this->companyAdmins;
    }

    /**
     *
     * @param Collection $companyAdmins
     *
     * @return Company
     */
    public function setCompanyAdmins(Collection $companyAdmins)
    {
        $this->companyAdmins = $companyAdmins;

        return $this;
    }

    /**
     * Add account
     *
     * @param Account $account
     *
     * @return Company
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param Account $account
     *
     * @return Company
     */
    public function removeAccount(Account $account)
    {
        $this->accounts->removeElement($account);

        return $this;
    }

    /**
     * Get accounts
     *
     * @return ArrayCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     *
     * @param Collection $accounts
     *
     * @return Company
     */
    public function setAccounts(Collection $accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Get banks
     *
     * @return ArrayCollection
     */
    public function getBanks()
    {
        $banks = new ArrayCollection();

        foreach ($this->accounts as $account) {
            if ($account instanceof Bank) {
                $banks->add($account);
            }
        }

        return $banks;
    }

    /**
     * Get funds
     *
     * @return ArrayCollection
     */
    public function getFunds()
    {
        $funds = new ArrayCollection();

        foreach ($this->accounts as $account) {
            if ($account instanceof Fund) {
                $funds->add($account);
            }
        }

        return $funds;
    }

    /**
     * Add withholding
     *
     * @param Withholding $withholding
     *
     * @return Company
     */
    public function addWithholding(Withholding $withholding)
    {
        $this->withholdings[] = $withholding;

        return $this;
    }

    /**
     * Remove withholding
     *
     * @param Withholding $withholding
     *
     * @return Company
     */
    public function removeWithholding(Withholding $withholding)
    {
        $this->withholdings->removeElement($withholding);

        return $this;
    }

    /**
     * Get withholdings
     *
     * @return ArrayCollection
     */
    public function getWithholdings()
    {
        return $this->withholdings;
    }

    /**
     *
     * @param Collection $withholdings
     *
     * @return Company
     */
    public function setWithholdings(Collection $withholdings)
    {
        $this->withholdings = $withholdings;

        return $this;
    }

    /**
     * Add monthlyBalance
     *
     * @param MonthlyBalance $monthlyBalance
     *
     * @return Company
     */
    public function addMonthlyBalance(MonthlyBalance $monthlyBalance)
    {
        $this->monthlyBalances[] = $monthlyBalance;

        return $this;
    }

    /**
     * Remove monthlyBalance
     *
     * @param MonthlyBalance $monthlyBalance
     *
     * @return Company
     */
    public function removeMonthlyBalance(MonthlyBalance $monthlyBalance)
    {
        $this->monthlyBalances->removeElement($monthlyBalance);

        return $this;
    }

    /**
     * Get monthlyBalances
     *
     * @return ArrayCollection
     */
    public function getMonthlyBalances()
    {
        return $this->monthlyBalances;
    }

    /**
     *
     * @param Collection $monthlyBalances
     *
     * @return Company
     */
    public function setMonthlyBalances(Collection $monthlyBalances)
    {
        $this->monthlyBalances = $monthlyBalances;

        return $this;
    }

    /**
     * Add paye
     *
     * @param MPaye $paye
     *
     * @return Company
     */
    public function addPaye(MPaye $paye)
    {
        $this->payes[] = $paye;

        return $this;
    }

    /**
     * Remove paye
     *
     * @param MPaye $paye
     *
     * @return Company
     */
    public function removePaye(MPaye $paye)
    {
        $this->payes->removeElement($paye);

        return $this;
    }

    /**
     * Get payes
     *
     * @return ArrayCollection
     */
    public function getPayes()
    {
        return $this->payes;
    }

    /**
     *
     * @param Collection $payes
     *
     * @return Company
     */
    public function setPayes(Collection $payes)
    {
        $this->payes = $payes;

        return $this;
    }

    /**
     * Get purchases
     *
     * @return ArrayCollection
     */
    public function getPurchases()
    {
        $purchases = new ArrayCollection();
        foreach ($this->monthlyBalances as $monthyBalance) {
            if ($monthyBalance instanceof MBPurchase) {
                $purchases->add($monthyBalance);
            }
        }

        return $purchases;
    }

    /**
     * Get sales
     *
     * @return ArrayCollection
     */
    public function getSales()
    {
        $sales = new ArrayCollection();
        foreach ($this->monthlyBalances as $monthyBalance) {
            if ($monthyBalance instanceof MBSale) {
                $sales->add($monthyBalance);
            }
        }

        return $sales;
    }

    /**
     * Get sales
     *
     * @return ArrayCollection
     */
    public function getSalaries()
    {
        $salaries = new ArrayCollection();
        foreach ($this->payes as $mpaye) {
            if ($mpaye instanceof MPaye) {
                $salaries->add($mpaye);
            }
        }

        return $salaries;
    }

    /**
     * Add docgroup
     *
     * @param Docgroup $docgroup
     *
     * @return Company
     */
    public function addDocgroup(Docgroup $docgroup)
    {
        $this->docgroups[] = $docgroup;

        return $this;
    }

    /**
     * Remove docgroup
     *
     * @param Docgroup $docgroup
     *
     * @return Company
     */
    public function removeDocgroup(Docgroup $docgroup)
    {
        $this->docgroups->removeElement($docgroup);

        return $this;
    }

    /**
     * Get docgroups
     *
     * @return ArrayCollection
     */
    public function getDocgroups()
    {
        return $this->docgroups;
    }

    /**
     *
     * @param Collection $docgroups
     *
     * @return Company
     */
    public function setDocgroups(Collection $docgroups)
    {
        $this->docgroups = $docgroups;

        return $this;
    }

    /**
     * Add docgroupcomptable
     *
     * @param Docgroupcomptable $docgroupcomptable
     *
     * @return Company
     */
    public function addDocgroupcomptable(Docgroupcomptable $docgroupcomptable)
    {
        $this->docgroupcomptables[] = $docgroupcomptable;

        return $this;
    }

    /**
     * Remove docgroupcomptable
     *
     * @param Docgroupcomptable $docgroupcomptable
     *
     * @return Company
     */
    public function removeDocgroupcomptable(Docgroupcomptable $docgroupcomptable)
    {
        $this->docgroupcomptables->removeElement($docgroupcomptable);

        return $this;
    }

    /**
     * Get docgroupcomptables
     *
     * @return ArrayCollection
     */
    public function getDocgroupcomptables()
    {
        return $this->docgroupcomptables;
    }

    /**
     *
     * @param Collection $docgroupcomptables
     *
     * @return Company
     */
    public function setDocgroupcomptables(Collection $docgroupcomptables)
    {
        $this->docgroupcomptables = $docgroupcomptables;

        return $this;
    }

    /**
     * Add docgroupfiscal
     *
     * @param Docgroupfiscal $docgroupfiscal
     *
     * @return Company
     */
    public function addDocgroupfiscal(Docgroupfiscal $docgroupfiscal)
    {
        $this->docgroupfiscals[] = $docgroupfiscal;

        return $this;
    }

    /**
     * Remove docgroupfiscal
     *
     * @param Docgroupfiscal $docgroupfiscal
     *
     * @return Company
     */
    public function removeDocgroupfiscal(Docgroupfiscal $docgroupfiscal)
    {
        $this->docgroupfiscals->removeElement($docgroupfiscal);

        return $this;
    }

    /**
     * Get docgroupfiscals
     *
     * @return ArrayCollection
     */
    public function getDocgroupfiscals()
    {
        return $this->docgroupfiscals;
    }

    /**
     *
     * @param Collection $docgroupfiscals
     *
     * @return Company
     */
    public function setDocgroupfiscals(Collection $docgroupfiscals)
    {
        $this->docgroupfiscals = $docgroupfiscals;

        return $this;
    }

    /**
     * Add docgroupperso
     *
     * @param Docgroupperso $docgroupperso
     *
     * @return Company
     */
    public function addDocgroupperso(Docgroupperso $docgroupperso)
    {
        $this->docgrouppersos[] = $docgroupperso;

        return $this;
    }

    /**
     * Remove docgroupperso
     *
     * @param Docgroupperso $docgroupperso
     *
     * @return Company
     */
    public function removeDocgroupperso(Docgroupperso $docgroupperso)
    {
        $this->docgrouppersos->removeElement($docgroupperso);

        return $this;
    }

    /**
     * Get docgrouppersos
     *
     * @return ArrayCollection
     */
    public function getDocgrouppersos()
    {
        return $this->docgrouppersos;
    }

    /**
     *
     * @param Collection $docgrouppersos
     *
     * @return Company
     */
    public function setDocgrouppersos(Collection $docgrouppersos)
    {
        $this->docgrouppersos = $docgrouppersos;

        return $this;
    }

    /**
     * Add docgroupsyst
     *
     * @param Docgroupsyst $docgroupsyst
     *
     * @return Company
     */
    public function addDocgroupsyst(Docgroupsyst $docgroupsyst)
    {
        $this->docgroupsysts[] = $docgroupsyst;

        return $this;
    }

    /**
     * Remove docgroupsyst
     *
     * @param Docgroupsyst $docgroupsyst
     *
     * @return Company
     */
    public function removeDocgroupsyst(Docgroupsyst $docgroupsyst)
    {
        $this->docgroupsysts->removeElement($docgroupsyst);

        return $this;
    }

    /**
     * Get docgroupsysts
     *
     * @return ArrayCollection
     */
    public function getDocgroupsysts()
    {
        return $this->docgroupsysts;
    }

    /**
     *
     * @param Collection $docgroupsysts
     *
     * @return Company
     */
    public function setDocgroupsysts(Collection $docgroupsysts)
    {
        $this->docgroupsysts = $docgroupsysts;

        return $this;
    }

    /**
     * Add docgroupbank
     *
     * @param Docgroupbank $docgroupbank
     *
     * @return Company
     */
    public function addDocgroupbank(Docgroupbank $docgroupbank)
    {
        $this->docgroupbanks[] = $docgroupbank;

        return $this;
    }

    /**
     * Remove docgroupbank
     *
     * @param Docgroupbank $docgroupbank
     *
     * @return Company
     */
    public function removeDocgroupbank(Docgroupbank $docgroupbank)
    {
        $this->docgroupbanks->removeElement($docgroupbank);

        return $this;
    }

    /**
     * Get docgroupbanks
     *
     * @return ArrayCollection
     */
    public function getDocgroupbanks()
    {
        return $this->docgroupbanks;
    }

    /**
     *
     * @param Collection $docgroupbanks
     *
     * @return Company
     */
    public function setDocgroupbanks(Collection $docgroupbanks)
    {
        $this->docgroupbanks = $docgroupbanks;

        return $this;
    }

    /**
     * Add docgroupaudit
     *
     * @param Docgroupaudit $docgroupaudit
     *
     * @return Company
     */
    public function addDocgroupaudit(Docgroupaudit $docgroupaudit)
    {
        $this->docgroupaudits[] = $docgroupaudit;

        return $this;
    }

    /**
     * Remove docgroupaudit
     *
     * @param Docgroupaudit $docgroupaudit
     *
     * @return Company
     */
    public function removeDocgroupaudit(Docgroupaudit $docgroupaudit)
    {
        $this->docgroupaudits->removeElement($docgroupaudit);

        return $this;
    }

    /**
     * Get docgroupaudits
     *
     * @return ArrayCollection
     */
    public function getDocgroupaudits()
    {
        return $this->docgroupaudits;
    }

    /**
     *
     * @param Collection $docgroupaudits
     *
     * @return Company
     */
    public function setDocgroupaudits(Collection $docgroupaudits)
    {
        $this->docgroupaudits = $docgroupaudits;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return Company
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return Company
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);

        return $this;
    }

    /**
     * Get docs
     *
     * @return ArrayCollection
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     *
     * @param Collection $docs
     *
     * @return Company
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     * Choice Form physicaltype
     *
     * @return multitype:string
     */
    public static function choicePhysicaltype()
    {
        return array(
            'Company.physicaltype.choice.' . self::PHTYPE_MORAL => self::PHTYPE_MORAL,
            'Company.physicaltype.choice.' . self::PHTYPE_PHYSIC => self::PHTYPE_PHYSIC
        );
    }

    /**
     * Choice Validator physicaltype
     *
     * @return multitype:integer
     */
    public static function choicePhysicaltypeCallback()
    {
        return array(
            self::PHTYPE_MORAL,
            self::PHTYPE_PHYSIC
        );
    }

    /**
     */
    public function __clone()
    {
        if ($this->id) {
            $sectors = $this->getSectors();
            $this->sectors = new ArrayCollection();
            foreach ($sectors as $sector) {
                $cloneSector = clone $sector;
                $this->sectors->add($cloneSector);
            }
        }
    }
}
