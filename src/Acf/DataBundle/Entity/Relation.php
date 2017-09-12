<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sasedev\Commons\SharedBundle\Validator as ExtraAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Relation
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_company_relations")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\RelationRepository")
 *         @ORM\InheritanceType("SINGLE_TABLE")
 *         @ORM\DiscriminatorColumn(name="relationtype", type="string")
 *         @ORM\DiscriminatorMap({"1" = "Customer", "2" = "Supplier"})
 *         @ORM\HasLifecycleCallbacks
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
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="relations", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var string @ORM\Column(name="label", type="text", nullable=false)
     */
    protected $label;

    /**
     *
     * @var integer @ORM\Column(name="numb", type="bigint", nullable=true)
     *      @Assert\GreaterThan(value="0", groups={"number"})
     *      @Assert\LessThan(value="1000000000", groups={"number"})
     */
    protected $number;

    /**
     *
     * @var string @ORM\Column(name="fisc", type="text", nullable=true)
     */
    protected $fisc;

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
     *      @ExtraAssert\Phone(groups={"fax"})
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
     * @var Collection @ORM\ManyToMany(targetEntity="Sector", inversedBy="relations", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_relation_sectors",
     *      joinColumns={
     *      @ORM\JoinColumn(name="relation_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $sectors;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Transaction", mappedBy="relation", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"number" = "ASC"})
     */
    protected $transactions;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Doc", mappedBy="relations", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_relation_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="relation_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      }
     *      )
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
