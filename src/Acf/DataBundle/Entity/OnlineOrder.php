<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * OnlineOrder
 *
 * @author sasedev <seif.salah@gmail.com>
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
     * @var User
     */
    protected $user;

    /**
     *
     * @var float
     */
    protected $val;

    /**
     *
     * @var string
     */
    protected $orderTo;

    /**
     *
     * @var string
     */
    protected $auth;

    /**
     *
     * @var string
     */
    protected $sessId;

    /**
     *
     * @var string
     */
    protected $ipAddr;

    /**
     *
     * @var integer
     */
    protected $paymentType;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $renew;

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
     * @var OnlineInvoice
     */
    protected $invoice;

    /**
     *
     * @var Collection
     */
    protected $products;

    /**
     *
     * @var Collection
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
    {
    }
}