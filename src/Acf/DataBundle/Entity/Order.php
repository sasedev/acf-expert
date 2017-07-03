<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order
 * @ORM\Table(name="acf_orders")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
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
     * @var string @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var float @ORM\Column(name="val", type="float", nullable=false)
     *      @Assert\GreaterThan(value=0, groups={"val"})
     */
    protected $val;

    /**
     *
     * @var integer @ORM\Column(name="status", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceStatusCallback", groups={"status"})
     */
    protected $status;

    /**
     *
     * @var string @ORM\Column(name="auth", type="text", nullable=true)
     */
    protected $auth;

    /**
     *
     * @var string @ORM\Column(name="session_id", type="text", nullable=true)
     */
    protected $sessId;

    /**
     *
     * @var string @ORM\Column(name="ip_addr", type="text", nullable=true)
     */
    protected $ipAddr;

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
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $bytes = \openssl_random_pseudo_bytes(10);
        $ref = \bin2hex($bytes);
        $this->setRef('ACF'.$ref);

        $this->val = 0;
        $this->status = self::ST_NEW;
    }

    /**
     * Get id
     *
     * @return guid
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