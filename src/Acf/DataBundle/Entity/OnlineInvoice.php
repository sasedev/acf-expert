<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_online_invoices")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineInvoiceRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 */
class OnlineInvoice
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
   * @var guid @ORM\Column(name="id", type="guid", nullable=false)
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
   * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="invoices", cascade={"persist"})
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
   * @var integer @ORM\Column(name="payment_type", type="integer", nullable=false)
   *      @Assert\Choice(callback="choicePaymentTypeCallback", groups={"type"})
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
   *      @Assert\Choice(callback="choiceRenewCallback", groups={"status"})
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
   * @var OnlineOrder @ORM\OneToOne(targetEntity="OnlineOrder", inversedBy="invoice")
   *      @ORM\JoinColumn(name="ord_id", referencedColumnName="id")
   */
  protected $order;

  /**
   *
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineProduct", inversedBy="invoices")
   *      @ORM\JoinTable(name="acf_online_invoice_elements",
   *      joinColumns={
   *      @ORM\JoinColumn(name="inv_id", referencedColumnName="id")
   *      },
   *      inverseJoinColumns={
   *      @ORM\JoinColumn(name="prd_id", referencedColumnName="id")
   *      }
   *      )
   */
  protected $products;

  /**
   *
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineTaxe", inversedBy="invoices")
   *      @ORM\JoinTable(name="acf_online_invoice_taxes",
   *      joinColumns={
   *      @ORM\JoinColumn(name="inv_id", referencedColumnName="id")
   *      },
   *      inverseJoinColumns={
   *      @ORM\JoinColumn(name="tx_id", referencedColumnName="id")
   *      }
   *      )
   */
  protected $taxes;

  /**
   * Constructor
   */
  public function __construct()
  {
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
   * @return OnlineInvoice
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
   * @return OnlineInvoice
   */
  public function setUser(User $user)
  {
    $this->user = $user;
    return $this;
  }

  /**
   *
   * @return Company $user
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   *
   * @param Company $company
   *
   * @return OnlineInvoice
   */
  public function setCompany(Company $company = null)
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
   * @return OnlineInvoice
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
   * @return OnlineInvoice
   */
  public function setOrderTo($orderTo)
  {
    $this->orderTo = $orderTo;
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
   * @return OnlineInvoice
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
   * @return OnlineInvoice
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
   * @return OnlineInvoice
   */
  public function setRenew($renew)
  {
    $this->renew = $renew;
    return $this;
  }

  /**
   *
   * @return Da\DateTimeteTime $dtCrea
   */
  public function getDtCrea()
  {
    return $this->dtCrea;
  }

  /**
   *
   * @param \DateTime $dtCrea
   *
   * @return OnlineInvoice
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
   * @return OnlineInvoice
   */
  public function setDtUpdate(\DateTime $dtUpdate = null)
  {
    $this->dtUpdate = $dtUpdate;
    return $this;
  }

  /**
   *
   * @return OnlineOrder $order
   */
  public function getOrder()
  {
    return $this->order;
  }

  /**
   *
   * @param OnlineOrder $order
   *
   * @return OnlineInvoice
   */
  public function setOrder(OnlineOrder $order)
  {
    $this->order = $order;
    return $this;
  }

  /**
   * Add product
   *
   * @param OnlineProduct $product
   *
   * @return OnlineInvoice
   */
  public function addProduct(OnlineProduct $product)
  {
    $this->products[] = $product;

    return $this;
  }

  /**
   * Remove product
   *
   * @param OnlineProduct $product
   *
   * @return OnlineInvoice
   */
  public function removeProduct(OnlineProduct $product)
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
   * @return OnlineInvoice
   */
  public function setProducts(Collection $products)
  {
    $this->products = $products;
    return $this;
  }

  /**
   * Add taxe
   *
   * @param OnlineTaxe $taxe
   *
   * @return OnlineInvoice
   */
  public function addTaxe(OnlineTaxe $taxe)
  {
    $this->taxes[] = $taxe;

    return $this;
  }

  /**
   * Remove taxe
   *
   * @param OnlineTaxe $taxe
   *
   * @return OnlineInvoice
   */
  public function removeTaxe(OnlineTaxe $taxe)
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
   * @return OnlineInvoice
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
      'OnlineInvoice.paymentType.choice.' . self::PTYPE_VRT => self::PTYPE_VRT,
      'OnlineInvoice.paymentType.choice.' . self::PTYPE_CHECK => self::PTYPE_CHECK,
      'OnlineInvoice.paymentType.choice.' . self::PTYPE_MONEY => self::PTYPE_MONEY,
      'OnlineInvoice.paymentType.choice.' . self::PTYPE_ONLINE => self::PTYPE_ONLINE
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
      'OnlineInvoice.status.choice.' . self::ST_NEW => self::ST_NEW,
      'OnlineInvoice.status.choice.' . self::ST_WAITING => self::ST_WAITING,
      'OnlineInvoice.status.choice.' . self::ST_OK => self::ST_OK,
      'OnlineInvoice.status.choice.' . self::ST_REFUSAL => self::ST_REFUSAL,
      'OnlineInvoice.status.choice.' . self::ST_CANCELED => self::ST_CANCELED,
      'OnlineInvoice.status.choice.' . self::ST_ERROR => self::ST_ERROR
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
      'OnlineInvoice.renew.choice.' . self::RENEW_NO => self::RENEW_NO,
      'OnlineInvoice.renew.choice.' . self::RENEW_AUTO => self::RENEW_AUTO
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

  /**
   */
  public function __clone()
  {}
}