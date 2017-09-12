<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trace
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_traces")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\TraceRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class Trace
{

    /**
     *
     * @var string
     */
    const AE_USER = '01User';

    /**
     *
     * @var string
     */
    const AE_JOB = '02Job';

    /**
     *
     * @var string
     */
    const AE_TYPE = '03Type';

    /**
     *
     * @var string
     */
    const AE_SECTOR = '04Sector';

    /**
     *
     * @var string
     */
    const AE_COMPANY = '05Company';

    /**
     *
     * @var string
     */
    const AE_ADDRESS = '06Address';

    /**
     *
     * @var string
     */
    const AE_PHONE = '07Phone';

    /**
     *
     * @var string
     */
    const AE_FRAME = '08Frame';

    /**
     *
     * @var string
     */
    const AE_NATURE = '09Nature';

    /**
     *
     * @var string
     */
    const AE_LABEL = '10Label';

    /**
     *
     * @var string
     */
    const AE_CUSTOMER = '11Customer';

    /**
     *
     * @var string
     */
    const AE_SUPPLIER = '12Supplier';

    /**
     *
     * @var string
     */
    const AE_BANK = '13Bank';

    /**
     *
     * @var string
     */
    const AE_FUND = '14FUND';

    /**
     *
     * @var string
     */
    const AE_WHITHHOLDING = '15Withholding';

    /**
     *
     * @var string
     */
    const AE_MBPURCHASE = '16MBPurchase';

    /**
     *
     * @var string
     */
    const AE_MBSALE = '17MBSale';

    /**
     *
     * @var string
     */
    const AE_BUY = '18Buy';

    /**
     *
     * @var string
     */
    const AE_SALE = '19Sale';

    /**
     *
     * @var string
     */
    const AE_SECONDARYVAT = '20SecondaryVat';

    /**
     *
     * @var string
     */
    const AE_SHAREHOLDER = '21Shareholder';

    /**
     *
     * @var string
     */
    const AE_PILOT = '22Pilot';

    /**
     *
     * @var string
     */
    const AE_CUSER = '23CompanyUser';

    /**
     *
     * @var string
     */
    const AE_CADMIN = '24CompanyAdmin';

    /**
     *
     * @var string
     */
    const AE_DOC = '30Doc';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPCOMPTABLE = '31Docgroupcomptable';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPBANK = '32Docgroupbank';

    /**
     *
     * @var string
     */
    const AE_DOCGROUP = '33Docgroup';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPFISCAL = '34Docgroupfiscal';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPPERSO = '35Docgroupperso';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPSYST = '36Docgroupsyst';

    /**
     *
     * @var string
     */
    const AE_DOCGROUPAUDIT = '37Docgroupaudit';

    /**
     *
     * @var string
     */
    const AE_STOCK = '38Stock';

    /**
     *
     * @var integer
     */
    const AT_CREATE = 1;

    /**
     *
     * @var integer
     */
    const AT_UPDATE = 2;

    /**
     *
     * @var integer
     */
    const AT_DELETE = 3;

    /**
     *
     * @var integer
     */
    const UT_ANONYMOUS = 0;

    /**
     *
     * @var integer
     */
    const UT_SUPERADMIN = 1;

    /**
     *
     * @var integer
     */
    const UT_ADMIN = 2;

    /**
     *
     * @var integer
     */
    const UT_CLIENT = 3;

    /**
     *
     * @var integer @ORM\Column(name="id", type="bigint", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_traces_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="msg", type="text", nullable=true)
     */
    protected $msg;

    /**
     *
     * @var integer @ORM\Column(name="action_type", type="bigint", nullable=false)
     */
    protected $actionType;

    /**
     *
     * @var string @ORM\Column(name="action_entity", type="text", nullable=false)
     */
    protected $actionEntity;

    /**
     *
     * @var string @ORM\Column(name="action_id", type="text", nullable=true)
     */
    protected $actionId;

    /**
     *
     * @var string @ORM\Column(name="action_entity2", type="text", nullable=false)
     */
    protected $actionEntity2;

    /**
     *
     * @var string @ORM\Column(name="action_id2", type="text", nullable=true)
     */
    protected $actionId2;

    /**
     *
     * @var string @ORM\Column(name="action_entity3", type="text", nullable=false)
     */
    protected $actionEntity3;

    /**
     *
     * @var string @ORM\Column(name="action_id3", type="text", nullable=true)
     */
    protected $actionId3;

    /**
     *
     * @var string @ORM\Column(name="action_entity4", type="text", nullable=false)
     */
    protected $actionEntity4;

    /**
     *
     * @var string @ORM\Column(name="action_id4", type="text", nullable=true)
     */
    protected $actionId4;

    /**
     *
     * @var string @ORM\Column(name="company_id", type="guid", nullable=true)
     */
    protected $companyId;

    /**
     *
     * @var string @ORM\Column(name="user_id", type="text", nullable=true)
     */
    protected $userId;

    /**
     *
     * @var string @ORM\Column(name="user_fullname", type="text", nullable=true)
     */
    protected $userFullname;

    /**
     *
     * @var integer @ORM\Column(name="user_type", type="bigint", nullable=false)
     */
    protected $userType;

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
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return Trace
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get actionType
     *
     * @return integer
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Set actionType
     *
     * @param integer $actionType
     *
     * @return Trace
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Get actionEntity
     *
     * @return string
     */
    public function getActionEntity()
    {
        return $this->actionEntity;
    }

    /**
     * Set actionEntity
     *
     * @param string $actionEntity
     *
     * @return Trace
     */
    public function setActionEntity($actionEntity)
    {
        $this->actionEntity = $actionEntity;

        return $this;
    }

    /**
     * Get actionId
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->actionId;
    }

    /**
     * Set actionId
     *
     * @param string $actionId
     *
     * @return Trace
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Get actionEntity2
     *
     * @return string
     */
    public function getActionEntity2()
    {
        return $this->actionEntity2;
    }

    /**
     * Set actionEntity2
     *
     * @param string $actionEntity2
     *
     * @return Trace
     */
    public function setActionEntity2($actionEntity2)
    {
        $this->actionEntity2 = $actionEntity2;

        return $this;
    }

    /**
     * Get actionId2
     *
     * @return string
     */
    public function getActionId2()
    {
        return $this->actionId2;
    }

    /**
     * Set actionId2
     *
     * @param string $actionId2
     *
     * @return Trace
     */
    public function setActionId2($actionId2)
    {
        $this->actionId2 = $actionId2;

        return $this;
    }

    /**
     * Get actionEntity3
     *
     * @return string
     */
    public function getActionEntity3()
    {
        return $this->actionEntity3;
    }

    /**
     * Set actionEntity3
     *
     * @param string $actionEntity3
     *
     * @return Trace
     */
    public function setActionEntity3($actionEntity3)
    {
        $this->actionEntity3 = $actionEntity3;

        return $this;
    }

    /**
     * Get actionId3
     *
     * @return string
     */
    public function getActionId3()
    {
        return $this->actionId3;
    }

    /**
     * Set actionId3
     *
     * @param string $actionId3
     *
     * @return Trace
     */
    public function setActionId3($actionId3)
    {
        $this->actionId3 = $actionId3;

        return $this;
    }

    /**
     * Get actionEntity4
     *
     * @return string
     */
    public function getActionEntity4()
    {
        return $this->actionEntity4;
    }

    /**
     * Set actionEntity4
     *
     * @param string $actionEntity4
     *
     * @return Trace
     */
    public function setActionEntity4($actionEntity4)
    {
        $this->actionEntity4 = $actionEntity4;

        return $this;
    }

    /**
     * Get actionId4
     *
     * @return string
     */
    public function getActionId4()
    {
        return $this->actionId4;
    }

    /**
     * Set actionId4
     *
     * @param string $actionId4
     *
     * @return Trace
     */
    public function setActionId4($actionId4)
    {
        $this->actionId4 = $actionId4;

        return $this;
    }

    /**
     *
     * @param string $companyId
     *
     * @return Trace
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return Trace
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userFullname
     *
     * @return string
     */
    public function getUserFullname()
    {
        return $this->userFullname;
    }

    /**
     * Set userFullname
     *
     * @param string $userFullname
     *
     * @return Trace
     */
    public function setUserFullname($userFullname)
    {
        $this->userFullname = $userFullname;

        return $this;
    }

    /**
     * Get userType
     *
     * @return integer
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set userType
     *
     * @param integer $userType
     *
     * @return Trace
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Trace
     */
    public function setDtCrea($dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Trace
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }
}
