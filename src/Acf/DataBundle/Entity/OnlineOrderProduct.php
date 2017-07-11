<?php
namespace Acf\DataBundle\Entity;

/**
 * OnlineOrderProduct
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineOrderProduct
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var OnlineOrder
     */
    protected $order;

    /**
     *
     * @var OnlineProduct
     */
    protected $product;

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
    public function __construct(OnlineProduct $product = null)
    {
        if (null != $product) {
            $this->label = $product->getLabel();
            $this->price = $product->getPrice();
            $this->vat = $product->getVat();
            $this->product = $product;
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
     * @return OnlineOrderProduct
     */
    public function setOrder(OnlineOrder $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     *
     * @return OnlineProduct $product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     *
     * @param OnlineProduct $product
     *
     * @return OnlineOrderProduct
     */
    public function setProduct(OnlineProduct $product)
    {
        $this->product = $product;
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
     * @return OnlineOrderProduct
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
     * @return OnlineOrderProduct
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
     * @return OnlineOrderProduct
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
     * @return OnlineOrderProduct
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
     * @return OnlineOrderProduct
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;
        return $this;
    }
}