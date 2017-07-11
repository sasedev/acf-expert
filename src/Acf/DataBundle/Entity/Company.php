<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Company
 *
 * @author sasedev <seif.salah@gmail.com>
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
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $ref;

    /**
     *
     * @var string
     */
    protected $corporateName;

    /**
     *
     * @var CompanyType
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $tribunal;

    /**
     *
     * @var string
     */
    protected $fisc;

    /**
     *
     * @var string
     */
    protected $cnss;

    /**
     *
     * @var string
     */
    protected $cnssBureau;

    /**
     *
     * @var integer
     */
    protected $physicaltype;

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
    protected $commercialRegister;

    /**
     *
     * @var string
     */
    protected $commercialRegisterBureau;

    /**
     *
     * @var string
     */
    protected $customsCode;

    /**
     *
     * @var float
     */
    protected $actionvn;

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
    protected $fax;

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
     *
     * @var Collection
     */
    protected $stocks;

    /**
     *
     * @var Collection
     */
    protected $phones;

    /**
     *
     * @var Collection
     */
    protected $addresses;

    /**
     *
     * @var Collection
     */
    protected $companyFrames;

    /**
     *
     * @var Collection
     */
    protected $companyLabels;

    /**
     *
     * @var Collection
     */
    protected $shareholders;

    /**
     *
     * @var Collection
     */
    protected $pilots;

    /**
     *
     * @var Collection
     */
    protected $companyNatures;

    /**
     *
     * @var Collection
     */
    protected $relations;

    /**
     *
     * @var Collection
     */
    protected $sectors;

    /**
     *
     * @var Collection
     */
    protected $users;

    /**
     *
     * @var Collection
     */
    protected $companyUsers;

    /**
     *
     * @var Collection
     */
    protected $admins;

    /**
     *
     * @var Collection
     */
    protected $companyAdmins;

    /**
     *
     * @var Collection
     */
    protected $accounts;

    /**
     *
     * @var Collection
     */
    protected $withholdings;

    /**
     *
     * @var Collection
     */
    protected $monthlyBalances;

    /**
     *
     * @var Collection
     */
    protected $payes;

    /**
     *
     * @var Collection
     */
    protected $invoices;

    /**
     *
     * @var Collection
     */
    protected $docgroups;

    /**
     *
     * @var Collection
     */
    protected $docgroupcomptables;

    /**
     *
     * @var Collection
     */
    protected $docgroupfiscals;

    /**
     *
     * @var Collection
     */
    protected $docgrouppersos;

    /**
     *
     * @var Collection
     */
    protected $docgroupsysts;

    /**
     *
     * @var Collection
     */
    protected $docgroupbanks;

    /**
     *
     * @var Collection
     */
    protected $docgroupaudits;

    /**
     *
     * @var Collection
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
        $this->invoices = new ArrayCollection();
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
     * @return string
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
     * Add invoice
     *
     * @param OnlineInvoice $invoice
     *
     * @return Company
     */
    public function addInvoice(OnlineInvoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param OnlineInvoice $invoice
     *
     * @return Company
     */
    public function removeInvoice(OnlineInvoice $invoice)
    {
        $this->invoices->removeElement($invoice);

        return $this;
    }

    /**
     * Get payes
     *
     * @return ArrayCollection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     *
     * @param Collection $invoices
     *
     * @return Company
     */
    public function setInvoices(Collection $invoices)
    {
        $this->invoices = $invoices;

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
