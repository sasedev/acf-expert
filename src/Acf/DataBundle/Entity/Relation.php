<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Relation
 *
 * @author sasedev <seif.salah@gmail.com>
 */
abstract class Relation
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
    const TYPE_CUSTOMER = '1';

    /**
     *
     * @var string
     */
    const TYPE_SUPPLIER = '2';

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
    protected $label;

    /**
     *
     * @var integer
     */
    protected $number;

    /**
     *
     * @var string
     */
    protected $fisc;

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
     *
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
    protected $sectors;

    /**
     *
     * @var Collection
     */
    protected $transactions;

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
        $this->physicaltype = self::PHTYPE_MORAL;
        $this->sectors = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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
     * @return Relation
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Relation
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Relation
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get numberFormated
     *
     * @return integer
     */
    public function getNumberFormated()
    {
        return sprintf('%09d', $this->getNumber());
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
     * @return Relation
     */
    public function setFisc($fisc)
    {
        $this->fisc = $fisc;

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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
     */
    public function setCommercialRegister($commercialRegister)
    {
        $this->commercialRegister = $commercialRegister;

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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get string
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set $fax
     *
     * @param string $fax
     *
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
     * @return Relation
     */
    public function setDtCrea(\DateTime $dtCrea)
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
     * @return Relation
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add sector
     *
     * @param Sector $sector
     *
     * @return Relation
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
     * @return Relation
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
     * @return Relation
     */
    public function setSectors(Collection $sectors)
    {
        $this->sectors = $sectors;

        return $this;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return Relation
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param Transaction $transaction
     *
     * @return Relation
     */
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);

        return $this;
    }

    /**
     * Get transactions
     *
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     *
     * @param Collection $transactions
     *
     * @return Relation
     */
    public function setTransactions(Collection $transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return Relation
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
     * @return Relation
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
     * @return Relation
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
            'Relation.physicaltype.choice.' . self::PHTYPE_MORAL => self::PHTYPE_MORAL,
            'Relation.physicaltype.choice.' . self::PHTYPE_PHYSIC => self::PHTYPE_PHYSIC
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
     *
     * @return string
     */
    public function getFullAddress()
    {
        $adr = $this->getStreetNum() . ' ' . $this->getAddress() . ' ' . $this->getAddress2() . ' ' . $this->getTown() . ' ' . $this->getZipCode() . ' ' . $this->getCountry();

        return $adr;
    }

    /**
     */
    public function __clone()
    {
        if ($this->id) {
            $docs = $this->getDocs();
            $this->docs = new ArrayCollection();
            foreach ($docs as $doc) {
                $cloneDoc = clone $doc;
                $this->docs->add($cloneDoc);
            }
            $sectors = $this->getSectors();
            $this->sectors = new ArrayCollection();
            foreach ($sectors as $sector) {
                $cloneSector = clone $sector;
                $this->sectors->add($cloneSector);
            }
        }
    }
}
