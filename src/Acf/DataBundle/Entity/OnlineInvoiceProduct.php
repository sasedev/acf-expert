<?php
namespace Acf\DataBundle\Entity;

/**
 * OnlineInvoiceProduct
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceProduct
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var OnlineInvoice
     */
    protected $invoice;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var float
     */
    protected $price;

    /**
     *
     * @var float
     */
    protected $vat;

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
     * Constructor
     *
     * @param OnlineProduct $product
     *
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
     * @return OnlineInvoiceProduct
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
     * @return OnlineInvoiceProduct
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;
        return $this;
    }
}