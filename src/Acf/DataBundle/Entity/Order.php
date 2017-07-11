<?php
namespace Acf\DataBundle\Entity;

/**
 * Order
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Order
{

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
     * @var string
     */
    protected $description;

    /**
     *
     * @var float
     */
    protected $val;

    /**
     *
     * @var integer
     */
    protected $status;

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
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $bytes = \openssl_random_pseudo_bytes(10);
        $ref = \bin2hex($bytes);
        $this->setRef('ACF' . $ref);

        $this->val = 0;
        $this->status = self::ST_NEW;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     *
     * @param string $ref
     *
     * @return Order
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user
     *
     * @return Order
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     *
     * @return Order
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
    public function getVal()
    {
        return $this->val;
    }

    /**
     *
     * @param float $val
     *
     * @return Order
     */
    public function setVal($val)
    {
        $this->val = $val;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param integer $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     *
     * @param string $auth
     *
     * @return Order
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSessId()
    {
        return $this->sessId;
    }

    /**
     *
     * @param string $sessId
     *
     * @return Order
     */
    public function setSessId($sessId)
    {
        $this->sessId = $sessId;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getIpAddr()
    {
        return $this->ipAddr;
    }

    /**
     *
     * @param string $ipAddr
     *
     * @return Order
     */
    public function setIpAddr($ipAddr)
    {
        $this->ipAddr = $ipAddr;

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
     * @return Order
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
     * @return Order
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Choice Form sexe
     *
     * @return multitype:string
     */
    public static function choiceStatus()
    {
        return array(
            'Order.status.choice.' . self::ST_NEW => self::ST_NEW,
            'Order.status.choice.' . self::ST_WAITING => self::ST_WAITING,
            'Order.status.choice.' . self::ST_OK => self::ST_OK,
            'Order.status.choice.' . self::ST_REFUSAL => self::ST_REFUSAL,
            'Order.status.choice.' . self::ST_CANCELED => self::ST_CANCELED,
            'Order.status.choice.' . self::ST_ERROR => self::ST_ERROR
        );
    }

    /**
     * Choice Validator sexe
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
}