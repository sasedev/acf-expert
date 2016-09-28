<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_online_invoice_elements")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineInvoiceProductRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class OnlineInvoiceProduct
{

  /**
   *
   * @var guid @ORM\Column(name="id", type="guid", nullable=false)
   *      @ORM\Id
   *      @ORM\GeneratedValue(strategy="UUID")
   */
  protected $id;

  /**
   *
   * @var OnlineInvoice @ORM\ManyToOne(targetEntity="OnlineInvoice", inversedBy="products", cascade={"persist"})
   *      @ORM\JoinColumns({
   *      @ORM\JoinColumn(name="inv_id", referencedColumnName="id")
   *      })
   */
  protected $invoice;

  /**
   *
   * @var string @ORM\Column(name="prd_label", type="text", nullable=false)
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
   * @param OnlineProduct $product
   *          Constructor
   */
  public function __construct(OnlineOrderProduct $product = null)
  {
    if (null != $product) {
      $this->label = $product->getLabel();
      $this->price = $product->getPrice();
      $this->vat = $product->getVat();
    } else {
      $this->price = 0;
      $this->vat = 0;
    }

    $this->dtCrea = new \DateTime('now');
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
   * @return OnlineInvoiceProduct
   */
  public function setInvoice(OnlineInvoice $invoice)
  {
    $this->invoice = $invoice;
    return $this;
  }

  /**
   *
   * @return string $label
   */
  public function getLabel()
  {
    return $this->label;
  }

  /**
   *
   * @param string $label
   *
   * @return OnlineInvoiceProduct
   */
  public function setLabel($label)
  {
    $this->label = $label;
    return $this;
  }

  /**
   *
   * @return float $price
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   *
   * @param float $price
   *
   * @return OnlineInvoiceProduct
   */
  public function setPrice($price)
  {
    $this->price = $price;
    return $this;
  }

  /**
   *
   * @return float $vat
   */
  public function getVat()
  {
    return $this->vat;
  }

  /**
   *
   * @param float $vat
   *
   * @return OnlineInvoiceProduct
   */
  public function setVat($vat)
  {
    $this->vat = $vat;
    return $this;
  }

  /**
   *
   * @return DateTime $dtCrea
   */
  public function getDtCrea()
  {
    return $this->dtCrea;
  }

  /**
   *
   * @param \DateTime $dtCrea
   *
   * @return OnlineInvoiceProduct
   */
  public function setDtCrea(\DateTime $dtCrea = null)
  {
    $this->dtCrea = $dtCrea;
    return $this;
  }

  /**
   *
   * @return DateTime $dtUpdate
   */
  public function getDtUpdate()
  {
    return $this->dtUpdate;
  }

  /**
   *
   * @param \DateTime $dtUpdate
   *
   * @return OnlineInvoiceProduct
   */
  public function setDtUpdate(\DateTime $dtUpdate = null)
  {
    $this->dtUpdate = $dtUpdate;
    return $this;
  }
}