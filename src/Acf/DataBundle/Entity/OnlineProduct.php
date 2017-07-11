<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * OnlineProduct
 *
 * @author sasedev <seif.salah@gmail.com>
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
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $description;

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
     * @var integer
     */
    protected $lockout;

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
     * @var Collection
     */
    protected $orders;

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
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     *
     * @return OnlineProduct
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     *
     * @return OnlineProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @param OnlineOrderProduct $order
     *
     * @return OnlineProduct
     */
    public function addOrder(OnlineOrderProduct $order)
    {
        $order->setProduct($this);
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param OnlineOrderProduct $order
     *
     * @return OnlineProduct
     */
    public function removeOrder(OnlineOrderProduct $order)
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     *
     * @return ArrayCollection $products
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

    public function getOriginalName()
    {
        $str = $this->label . ' : ';
        $str .= number_format($this->price, 3, '.', '');
        $str .= ' TND (+' . number_format($this->vat, 2, '.', '');
        $str .= '%)';
        return $str;
    }

    /**
     */
    public function __clone()
    {
    }
}