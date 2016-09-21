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
 *         @ORM\Table(name="acf_online_taxes")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineTaxeRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label"}, errorPath="label", groups={"label"})
 */
class OnlineTaxe
{

  /**
   *
   * @var integer
   */
  const TYPE_NUMERIC = 1;

  /**
   *
   * @var integer
   */
  const TYPE_PERCENT = 2;

  /**
   *
   * @var integer
   */
  const VISIBLE_SHOW = 1;

  /**
   *
   * @var integer
   */
  const VISIBLE_HIDE = 2;

  /**
   *
   * @var guid @ORM\Column(name="id", type="guid", nullable=false)
   *      @ORM\Id
   *      @ORM\GeneratedValue(strategy="UUID")
   */
  protected $id;

  /**
   *
   * @var string @ORM\Column(name="tx_label", type="text", nullable=false, unique=true)
   *      @Assert\Length(min = "2", max = "100", groups={"label"})
   */
  protected $label;

  /**
   *
   * @var float @ORM\Column(name="tx_val", type="float", nullable=false)
   *      @Assert\NotNull(groups={"value"})
   */
  protected $value;

  /**
   *
   * @var integer @ORM\Column(name="tx_type", type="integer", nullable=false)
   *      @Assert\Choice(callback="choiceTypeCallback", groups={"lockout"})
   */
  protected $type;

  /**
   *
   * @var integer @ORM\Column(name="tx_actif", type="integer", nullable=false)
   *      @Assert\Choice(callback="choiceVisibleCallback", groups={"lockout"})
   */
  protected $visible;

  /**
   *
   * @var integer @ORM\Column(name="tx_priority", type="integer", nullable=false)
   */
  protected $priority;

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
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineOrder", mappedBy="taxes")
   *      @ORM\JoinTable(name="acf_online_order_taxes",
   *      joinColumns={
   *      @ORM\JoinColumn(name="tx_id", referencedColumnName="id")
   *      },
   *      inverseJoinColumns={
   *      @ORM\JoinColumn(name="ord_id", referencedColumnName="id")
   *      }
   *      )
   */
  protected $orders;

  /**
   *
   * @var Collection @ORM\ManyToMany(targetEntity="OnlineInvoice", mappedBy="taxes")
   *      @ORM\JoinTable(name="acf_online_invoice_taxes",
   *      joinColumns={
   *      @ORM\JoinColumn(name="tx_id", referencedColumnName="id")
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
    $this->value = 0;
    $this->type = self::TYPE_NUMERIC;
    $this->visible = self::VISIBLE_SHOW;
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
   * @return OnlineTaxe
   */
  public function setLabel($label)
  {
    $this->label = $label;
    return $this;
  }

  /**
   *
   * @return float $value
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   *
   * @param float $value
   *
   * @return OnlineTaxe
   */
  public function setValue($value)
  {
    $this->value = $value;
    return $this;
  }

  /**
   *
   * @return integer $type
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   *
   * @param integer $type
   *
   * @return OnlineTaxe
   */
  public function setType($type)
  {
    $this->type = $type;
    return $this;
  }

  /**
   *
   * @return integer $visible
   */
  public function getVisible()
  {
    return $this->visible;
  }

  /**
   *
   * @param integer $visible
   *
   * @return OnlineTaxe
   */
  public function setVisible($visible)
  {
    $this->visible = $visible;
    return $this;
  }

  /**
   *
   * @return integer $priority
   */
  public function getPriority()
  {
    return $this->priority;
  }

  /**
   *
   * @param integer $priority
   *
   * @return OnlineTaxe
   */
  public function setPriority($priority)
  {
    $this->priority = $priority;
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
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
   * @return OnlineTaxe
   */
  public function setInvoices(Collection $invoices)
  {
    $this->invoices = $invoices;
    return $this;
  }

  /**
   * Choice Form type
   *
   * @return multitype:string
   */
  public static function choiceType()
  {
    return array(
      'OnlineTaxe.type.choice.' . self::TYPE_NUMERIC => self::TYPE_NUMERIC,
      'OnlineTaxe.type.choice.' . self::TYPE_PERCENT => self::TYPE_PERCENT
    );
  }

  /**
   * Choice Validator type
   *
   * @return multitype:string
   */
  public static function choiceTypeCallback()
  {
    return array(
      self::TYPE_NUMERIC,
      self::TYPE_PERCENT
    );
  }

  /**
   * Choice Form visible
   *
   * @return multitype:string
   */
  public static function choiceVisible()
  {
    return array(
      'OnlineTaxe.visible.choice.' . self::VISIBLE_SHOW => self::VISIBLE_SHOW,
      'OnlineTaxe.visible.choice.' . self::VISIBLE_HIDE => self::VISIBLE_HIDE
    );
  }

  /**
   * Choice Validator lockout
   *
   * @return multitype:string
   */
  public static function choiceVisibleCallback()
  {
    return array(
      self::VISIBLE_SHOW,
      self::VISIBLE_HIDE
    );
  }

  /**
   */
  public function __clone()
  {}
}
