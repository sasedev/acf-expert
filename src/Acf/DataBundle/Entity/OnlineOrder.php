<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OnlineOrder
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_online_orders")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineOrderRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 */
class OnlineOrder
{

    /**
     *
     * @var integer
     */
    const PTYPE_VRT = 1;

    /**
     *
     * @var integer
     */
    const PTYPE_CHECK = 2;

    /**
     *
     * @var integer
     */
    const PTYPE_MONEY = 3;

    /**
     *
     * @var integer
     */
    const PTYPE_ONLINE = 4;

    /**
     *
     * @var integer
     */
    const ST_NEW = 1;

    /**
     *
     * @var integer
     */
    const ST_WAITING = 2;

    /**
     *
     * @var integer
     */
    const ST_OK = 3;

    /**
     *
     * @var integer
     */
    const ST_REFUSAL = 4;

    /**
     *
     * @var integer
     */
    const ST_CANCELED = 5;

    /**
     *
     * @var integer
     */
    const ST_ERROR = 6;

    /**
     *
     * @var integer
     */
    const RENEW_AUTO = 1;

    /**
     *
     * @var integer
     */
    const RENEW_NO = 2;

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="ref", type="text", nullable=false, unique=true)
     */
    protected $ref;

    /**
     *
     * @var User @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      })
     */
    protected $user;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="onlineOrders", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var float @ORM\Column(name="val", type="float", nullable=false)
     *      @Assert\GreaterThan(value=0, groups={"val"})
     */
    protected $val;

    /**
     *
     * @var string @ORM\Column(name="orderto", type="text", nullable=false)
     *      @Assert\NotBlank(groups={"orderTo"})
     */
    protected $orderTo;

    /**
     *
     * @var string @ORM\Column(name="auth", type="text", nullable=true)
     */
    protected $auth;

    /**
     *
     * @var string @ORM\Column(name="session_id", type="text", nullable=true)
     */
    protected $sessId;

    /**
     *
     * @var string @ORM\Column(name="ip_addr", type="text", nullable=true)
     */
    protected $ipAddr;

    /**
     *
     * @var integer @ORM\Column(name="payment_type", type="integer", nullable=false)
     *      @Assert\Choice(callback="choicePaymentTypeCallback", groups={"paymentType"})
     */
    protected $paymentType;

    /**
     *
     * @var integer @ORM\Column(name="payment_status", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceStatusCallback", groups={"status"})
     */
    protected $status;

    /**
     *
     * @var integer @ORM\Column(name="autorenew", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceRenewCallback", groups={"renew"})
     */
    protected $renew;

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
     * @var OnlineInvoice @ORM\OneToOne(targetEntity="OnlineInvoice", mappedBy="order")
     */
    protected $invoice;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="OnlineOrderProduct", mappedBy="order", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $products;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="OnlineOrderTaxe", mappedBy="order", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"priority" = "ASC"})
     */
    protected $taxes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $bytes = \openssl_random_pseudo_bytes(10);
        $ref = \bin2hex($bytes);
        $this->setRef('ACF' . $ref);
        $this->val = 0;
        $this->status = self::ST_NEW;
        $this->paymentType = self::PTYPE_ONLINE;
        $this->renew = self::RENEW_AUTO;
        $this->dtCrea = new \DateTime('now');
        $this->products = new ArrayCollection();
        $this->taxes = new ArrayCollection();
    }

    /**
     * Get $id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string $ref
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     *
     * @param string $ref
     *
     * @return OnlineOrder
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user
     *
     * @return OnlineOrder
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param Company $company
     *
     * @return OnlineOrder
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     *
     * @return float $val
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     *
     * @param float $val
     *
     * @return OnlineOrder
     */
    public function setVal($val)
    {
        $this->val = $val;

        return $this;
    }

    /**
     *
     * @return string $orderTo
     */
    public function getOrderTo()
    {
        return $this->orderTo;
    }

    /**
     *
     * @param string $orderTo
     *
     * @return OnlineOrder
     */
    public function setOrderTo($orderTo)
    {
        $this->orderTo = $orderTo;
        return $this;
    }

    /**
     *
     * @return string $auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     *
     * @param string $auth
     *
     * @return OnlineOrder
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     *
     * @return string $sessId
     */
    public function getSessId()
    {
        return $this->sessId;
    }

    /**
     *
     * @param string $sessId
     *
     * @return OnlineOrder
     */
    public function setSessId($sessId)
    {
        $this->sessId = $sessId;
        return $this;
    }

    /**
     *
     * @return string $ipAddr
     */
    public function getIpAddr()
    {
        return $this->ipAddr;
    }

    /**
     *
     * @param string $ipAddr
     *
     * @return OnlineOrder
     */
    public function setIpAddr($ipAddr)
    {
        $this->ipAddr = $ipAddr;
        return $this;
    }

    /**
     *
     * @return integer $paymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     *
     * @param integer $paymentType
     *
     * @return OnlineOrder
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     *
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param integer $status
     *
     * @return OnlineOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     *
     * @return integer $renew
     */
    public function getRenew()
    {
        return $this->renew;
    }

    /**
     *
     * @param integer $renew
     *
     * @return OnlineOrder
     */
    public function setRenew($renew)
    {
        $this->renew = $renew;
        return $this;
    }

    /**
     *
     * @return \DateTime $dtCrea
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     *
     * @param \DateTime $dtCrea
     *
     * @return OnlineOrder
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;
        return $this;
    }

    /**
     *
     * @return \DateTime $dtUpdate
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     *
     * @param \DateTime $dtUpdate
     *
     * @return OnlineOrder
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;
        return $this;
    }

    /**
     *
     * @return OnlineInvoice $invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     *
     * @param OnlineInvoice $invoice
     *
     * @return OnlineOrder
     */
    public function setInvoice(OnlineInvoice $invoice = null)
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * Add product
     *
     * @param OnlineOrderProduct $product
     *
     * @return OnlineOrder
     */
    public function addProduct(OnlineOrderProduct $product)
    {
        $product->setOrder($this);
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param OnlineOrderProduct $product
     *
     * @return OnlineOrder
     */
    public function removeProduct(OnlineOrderProduct $product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     *
     * @return ArrayCollection $products
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     *
     * @param Collection $products
     *
     * @return OnlineOrder
     */
    public function setProducts(Collection $products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Add taxe
     *
     * @param OnlineOrderTaxe $taxe
     *
     * @return OnlineOrder
     */
    public function addTaxe(OnlineOrderTaxe $taxe)
    {
        $taxe->setOrder($this);
        $this->taxes[] = $taxe;

        return $this;
    }

    /**
     * Remove taxe
     *
     * @param OnlineOrderTaxe $taxe
     *
     * @return OnlineOrder
     */
    public function removeTaxe(OnlineOrderTaxe $taxe)
    {
        $this->taxes->removeElement($taxe);

        return $this;
    }

    /**
     *
     * @return ArrayCollection $taxes
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     *
     * @param Collection $taxes
     *
     * @return OnlineOrder
     */
    public function setTaxes(Collection $taxes)
    {
        $this->taxes = $taxes;
        return $this;
    }

    /**
     * Choice Form paymentType
     *
     * @return multitype:string
     */
    public static function choicePaymentType()
    {
        return array(
            'OnlineOrder.paymentType.choice.' . self::PTYPE_VRT => self::PTYPE_VRT,
            'OnlineOrder.paymentType.choice.' . self::PTYPE_CHECK => self::PTYPE_CHECK,
            'OnlineOrder.paymentType.choice.' . self::PTYPE_MONEY => self::PTYPE_MONEY,
            'OnlineOrder.paymentType.choice.' . self::PTYPE_ONLINE => self::PTYPE_ONLINE
        );
    }

    /**
     * Choice Validator paymentType
     *
     * @return multitype:string
     */
    public static function choicePaymentTypeCallback()
    {
        return array(
            self::PTYPE_VRT,
            self::PTYPE_CHECK,
            self::PTYPE_MONEY,
            self::PTYPE_ONLINE
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
            'OnlineOrder.status.choice.' . self::ST_NEW => self::ST_NEW,
            'OnlineOrder.status.choice.' . self::ST_WAITING => self::ST_WAITING,
            'OnlineOrder.status.choice.' . self::ST_OK => self::ST_OK,
            'OnlineOrder.status.choice.' . self::ST_REFUSAL => self::ST_REFUSAL,
            'OnlineOrder.status.choice.' . self::ST_CANCELED => self::ST_CANCELED,
            'OnlineOrder.status.choice.' . self::ST_ERROR => self::ST_ERROR
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
            self::ST_NEW,
            self::ST_WAITING,
            self::ST_OK,
            self::ST_REFUSAL,
            self::ST_CANCELED,
            self::ST_ERROR
        );
    }

    /**
     * Choice Form renew
     *
     * @return multitype:string
     */
    public static function choiceRenew()
    {
        return array(
            'OnlineOrder.renew.choice.' . self::RENEW_NO => self::RENEW_NO,
            'OnlineOrder.renew.choice.' . self::RENEW_AUTO => self::RENEW_AUTO
        );
    }

    /**
     * Choice Validator renew
     *
     * @return multitype:string
     */
    public static function choiceRenewCallback()
    {
        return array(
            self::RENEW_NO,
            self::RENEW_AUTO
        );
    }

    public function updateVal()
    {
        $val = 0;
        foreach ($this->getProducts() as $oproduct) {
            $val += $oproduct->getPrice() + $oproduct->getPrice() * $oproduct->getVat() / 100;
        }
        foreach ($this->getTaxes() as $otaxe) {
            if ($otaxe->getType() == OnlineOrderTaxe::TYPE_NUMERIC) {
                $val += $otaxe->getValue();
            } else {
                $val = $val + $val * $otaxe->getValue() / 100;
            }
        }
        $this->setVal($val);
    }

    /**
     */
    public function __clone()
    {}
}