<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Intl\Intl;

/**
 * Lang
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Lang
{

    /**
     *
     * @var integer
     */
    const ST_DISABLED = 2;

    /**
     *
     * @var integer
     */
    const ST_ENABLED = 1;

    /**
     *
     * @var string
     */
    const DIR_LTR = 'ltr';

    /**
     *
     * @var string
     */
    const DIR_RTL = 'rtl';

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $locale;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $direction;

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
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = self::ST_DISABLED;
        $this->direction = self::DIR_LTR;
        $this->dtCrea = new \DateTime('now');
        $this->users = new ArrayCollection();
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
     * Get $locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set $locale
     *
     * @param string $locale
     *
     * @return Lang $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get $locale
     *
     * @return string
     */
    public function getFullLocale()
    {
        return \ucfirst(Intl::getLanguageBundle()->getLanguageName($this->locale));
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return \ucfirst(\Locale::getDisplayName($this->locale, $this->locale));
    }

    /**
     * Get $status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set $status
     *
     * @param integer $status
     *
     * @return Lang
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get $direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set $direction
     *
     * @param string $direction
     *
     * @return Lang
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get $dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set $dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Lang
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get \$dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set $dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Lang $this
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Lang
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     *
     * @return Lang
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set users
     *
     * @param Collection $users
     *
     * @return Lang
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Choice Form status
     *
     * @return multitype:string
     */
    public static function choiceStatus()
    {
        return array(
            'Lang.status.choice.' . self::ST_DISABLED => self::ST_DISABLED,
            'Lang.status.choice.' . self::ST_ENABLED => self::ST_ENABLED
        );
    }

    /**
     * Choice Validator status
     *
     * @return multitype:integer
     */
    public static function choiceStatusCallback()
    {
        return array(
            self::ST_DISABLED,
            self::ST_ENABLED
        );
    }

    /**
     * Choice Form direction
     *
     * @return multitype:string
     */
    public static function choiceDirection()
    {
        return array(
            'Lang.direction.choice.' . self::DIR_LTR => self::DIR_LTR,
            'Lang.direction.choice.' . self::DIR_RTL => self::DIR_RTL
        );
    }

    /**
     * Choice Validator status
     *
     * @return multitype:string
     */
    public static function choiceDirectionCallback()
    {
        return array(
            self::DIR_LTR,
            self::DIR_RTL
        );
    }

    /**
     */
    public function __clone()
    {
    }
}
