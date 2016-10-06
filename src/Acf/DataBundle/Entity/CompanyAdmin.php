<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_company_admins")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\CompanyAdminRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"user", "company"}, errorPath="user", groups={"user"})
 */
class CompanyAdmin
{

    /**
     *
     * @var integer
     */
    const CANT = 1;

    /**
     *
     * @var integer
     */
    const CAN = 2;

    /**
     *
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="companyAdmins", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var User @ORM\ManyToOne(targetEntity="User", inversedBy="companyAdmins", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      })
     */
    protected $user;

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
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param Company $company
     *
     * @return CompanyAdmin
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;

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
     * @return CompanyAdmin
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     *
     * @param \DateTime $dtCrea
     *
     * @return CompanyAdmin
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     *
     * @param \DateTime $dtUpdate
     *
     * @return CompanyAdmin
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Choice Form TrueFalse
     *
     * @return multitype:string
     */
    public static function choiceTF()
    {
        return array(
            'CompanyUser.tf.choice.' . self::CANT => self::CANT,
            'CompanyUser.tf.choice.' . self::CAN => self::CAN
        );
    }

    /**
     * Choice Validator TrueFalse
     *
     * @return multitype:integer
     */
    public static function choiceTFCallback()
    {
        return array(
            self::CANT,
            self::CAN
        );
    }

    /**
     */
    public function __clone()
    {}
}
