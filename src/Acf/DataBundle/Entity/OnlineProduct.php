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
 *         @ORM\Table(name="acf_online_products")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineProductRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label"}, errorPath="label", groups={"label"})
 */
class OnlineProduct
{

  /**
   *
   * @var integer
   */
  const LOCKOUT_UNLOCKED = 1;

  /**
   *
   * @var integer
   */
  const LOCKOUT_LOCKED = 2;

  /**
   *
   * @var guid @ORM\Column(name="id", type="guid", nullable=false)
   *      @ORM\Id
   *      @ORM\GeneratedValue(strategy="UUID")
   */
  protected $id;

  /**
   *
   * @var string @ORM\Column(name="prd_label", type="text", nullable=false, unique=true)
   *      @Assert\Length(min = "2", max = "100", groups={"label"})
   */
  protected $label;

  /**
   *
   * @var float @ORM\Column(name="prd_price_ht", type="float", nullable=false)
   *      @Assert\GreaterThan(value="0", groups={"price"})
   */
  protected $price;

  /**
   *
   * @var float @ORM\Column(name="prd_vat", type="float", nullable=false)
   *      @Assert\GreaterThanOrEqual(value="0", groups={"vat"})
   *      @Assert\LessThanOrEqual(value="100", groups={"vat"})
   */
  protected $vat;

  /**
   *
   * @var integer @ORM\Column(name="prd_lockout", type="integer", nullable=false)
   *      @Assert\Choice(callback="choiceLockoutCallback", groups={"lockout"})
   */
  protected $lockout;

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
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineOrder", mappedBy="products")
   *      @ORM\JoinTable(name="acf_online_order_elements",
   *      joinColumns={
   *      @ORM\JoinColumn(name="prd_id", referencedColumnName="id")
   *      },
   *      inverseJoinColumns={
   *      @ORM\JoinColumn(name="ord_id", referencedColumnName="id")
   *      }
   *      )
   */
  protected $orders;

  /**
   *
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineInvoice", mappedBy="products")
   *      @ORM\JoinTable(name="acf_online_invoice_elements",
   *      joinColumns={
   *      @ORM\JoinColumn(name="prd_id", referencedColumnName="id")
   *      },
   *      inverseJoinColumns={
   *      @ORM\JoinColumn(name="inv_id", referencedColumnName="id")
   *      }
   *      )
   */
  protected $invoices;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->price = 0;
    $this->vat = 0;
    $this->lockout = self::LOCKOUT_UNLOCKED;
    $this->dtCrea = new \DateTime('now');
    $this->orders = new ArrayCollection();
    $this->invoices = new ArrayCollection();
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
   * @return string
   */
  public function getLabel()
  {
    return $this->label;
  }

  /**
   *
   * @param string $label
   *
   * @return OnlineProduct
   */
  public function setLabel($label)
  {
    $this->label = $label;
    return $this;
  }

  /**
   *
   * @return float
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   *
   * @param float $price
   *
   * @return OnlineProduct
   */
  public function setPrice($price)
  {
    $this->price = $price;
    return $this;
  }

  /**
   *
   * @return float
   */
  public function getVat()
  {
    return $this->vat;
  }

  /**
   *
   * @param float $vat
   *
   * @return OnlineProduct
   */
  public function setVat($vat)
  {
    $this->vat = $vat;
    return $this;
  }

  /**
   *
   * @return integer
   */
  public function getLockout()
  {
    return $this->lockout;
  }

  /**
   *
   * @param integer $lockout
   *
   * @return OnlineProduct
   */
  public function setLockout($lockout)
  {
    $this->lockout = $lockout;
    return $this;
  }

  /**
   *
   * @return \DateTime
   */
  public function getDtCrea()
  {
    return $this->dtCrea;
  }

  /**
   *
   * @param \DateTime $dtCrea
   *
   * @return OnlineProduct
   */
  public function setDtCrea(\DateTime $dtCrea = null)
  {
    $this->dtCrea = $dtCrea;
    return $this;
  }

  /**
   *
   * @return \DateTime
   */
  public function getDtUpdate()
  {
    return $this->dtUpdate;
  }

  /**
   *
   * @param \DateTime $dtUpdate
   *
   * @return OnlineProduct
   */
  public function setDtUpdate(\DateTime $dtUpdate = null)
  {
    $this->dtUpdate = $dtUpdate;
    return $this;
  }

  /**
   * Add order
   *
   * @param OnlineOrder $order
   *
   * @return OnlineProduct
   */
  public function addOrder(OnlineOrder $order)
  {
    $this->orders[] = $order;

    return $this;
  }

  /**
   * Remove order
   *
   * @param OnlineOrder $order
   *
   * @return OnlineProduct
   */
  public function removeOrder(OnlineOrder $order)
  {
    $this->orders->removeElement($order);

    return $this;
  }

  /**
   *
   * @return ArrayCollection
   */
  public function getOrders()
  {
    return $this->orders;
  }

  /**
   *
   * @param Collection $orders
   *
   * @return OnlineProduct
   */
  public function setOrders(Collection $orders)
  {
    $this->orders = $orders;
    return $this;
  }

  /**
   * Add invoice
   *
   * @param OnlineInvoice $invoice
   *
   * @return OnlineProduct
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
   * @return OnlineProduct
   */
  public function removeInvoice(OnlineInvoice $invoice)
  {
    $this->invoices->removeElement($invoice);

    return $this;
  }

  /**
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
   * @return OnlineProduct
   */
  public function setInvoices(Collection $invoices)
  {
    $this->invoices = $invoices;
    return $this;
  }

  /**
   * Choice Form lockout
   *
   * @return multitype:string
   */
  public static function choiceLockout()
  {
    return array(
      'OnlineProduct.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
      'OnlineProduct.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
    );
  }

  /**
   * Choice Validator lockout
   *
   * @return multitype:string
   */
  public static function choiceLockoutCallback()
  {
    return array(
      self::LOCKOUT_UNLOCKED,
      self::LOCKOUT_LOCKED
    );
  }

  /**
   */
  public function __clone()
  {}
}