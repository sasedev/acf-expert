<?php
namespace Acf\DataBundle\Entity;

/**
 * CompanyAdmin
 *
 * @author sasedev <seif.salah@gmail.com>
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
     * @var string
     */
    protected $id;

    /**
     *
     * @var Company
     */
    protected $company;

    /**
     *
     * @var User
     */
    protected $user;

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
    }

    /**
     *
     * @return string
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
     * @return CompanyAdmin
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
    {
    }
}
