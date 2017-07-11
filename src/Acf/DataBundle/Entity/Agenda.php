<?php
namespace Acf\DataBundle\Entity;

use Acf\DataBundle\Model\FCEventClass;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Agenda extends FCEventClass
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var User
     */
    protected $user;

    /**
     *
     * @var \DateTime
     */
    protected $dtStart;

    /**
     *
     * @var \DateTime
     */
    protected $dtEnd;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $comment;

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
        $this->dtCrea = new \DateTime('now');
        $this->users = new ArrayCollection();
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
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Agenda
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get dtStart
     *
     * @return \DateTime
     */
    public function getDtStart()
    {
        return $this->dtStart;
    }

    /**
     * Set dtStart
     *
     * @param \DateTime $dtStart
     *
     * @return Agenda
     */
    public function setDtStart(\DateTime $dtStart)
    {
        $this->dtStart = $dtStart;

        return $this;
    }

    /**
     * Get dtEnd
     *
     * @return \DateTime
     */
    public function getDtEnd()
    {
        return $this->dtEnd;
    }

    /**
     * Set dtEnd
     *
     * @param \DateTime $dtEnd
     *
     * @return Agenda
     */
    public function setDtEnd(\DateTime $dtEnd)
    {
        $this->dtEnd = $dtEnd;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     *
     * @return Agenda
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     *
     * @param string $comment
     *
     * @return Agenda
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Set dtcrea
     *
     * @param \DateTime $dtcrea
     *
     * @return Agenda
     */
    public function setDtCrea(\DateTime $dtcrea)
    {
        $this->dtCrea = $dtcrea;

        return $this;
    }

    /**
     * Get dtcrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
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
     * @return Agenda
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Agenda
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
     * @return Agenda
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
     *
     * @param Collection $users
     *
     * @return Agenda
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Check user in list
     *
     * @param User $user
     *
     * @return boolean
     */
    public function isUserInAgenda(User $user)
    {
        foreach ($this->users as $agendaUser) {
            if ($agendaUser->getId() == $user->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     */
    public function __clone()
    {
    }
}
