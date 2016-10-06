<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_online_invoice_taxes")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OnlineInvoiceTaxeRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class OnlineInvoiceTaxe
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
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var OnlineInvoice @ORM\ManyToOne(targetEntity="OnlineInvoice", inversedBy="taxes", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="inv_id", referencedColumnName="id")
     *      })
     */
    protected $invoice;

    /**
     *
     * @var string @ORM\Column(name="tx_label", type="text", nullable=false)
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
     */
    protected $type;

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
     * @param OnlineTaxe $taxe
     *            Constructor
     */
    public function __construct(OnlineOrderTaxe $taxe = null)
    {
        if (null != $taxe) {
            $this->label = $taxe->getLabel();
            $this->type = $taxe->getType();
            $this->priority = $taxe->getPriority();
            $this->value = $taxe->getValue();
        } else {
            $this->value = 0;
            $this->type = self::TYPE_NUMERIC;
            $this->priority = 1;
        }

        $this->dtCrea = new \DateTime('now');
    }

    /**
     *
     * @return guid $id
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
     * @return OnlineInvoiceTaxe
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
     * @return OnlineInvoiceTaxe
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
     * @return OnlineInvoiceTaxe
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
     * @return OnlineInvoiceTaxe
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return OnlineInvoiceTaxe
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
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
     * @return OnlineInvoiceTaxe
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
     * @return OnlineInvoiceTaxe
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;
        return $this;
    }
}