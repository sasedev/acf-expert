<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Acf\DataBundle\Model\FCEventClass;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_agenda_events")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AgendaRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class Agenda extends FCEventClass
{

    /**
     *
     * @var guid @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var User @ORM\ManyToOne(targetEntity="User", inversedBy="events", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      })
     */
    protected $user;

    /**
     *
     * @var \DateTime @ORM\Column(name="evt_start", type="datetimetz", nullable=false)
     */
    protected $dtStart;

    /**
     *
     * @var \DateTime @ORM\Column(name="evt_end", type="datetimetz", nullable=false)
     */
    protected $dtEnd;

    /**
     *
     * @var string @ORM\Column(name="evt_title", type="text", nullable=true)
     */
    protected $title;

    /**
     *
     * @var string @ORM\Column(name="evt_comments", type="text", nullable=true)
     */
    protected $comment;

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
     *
     * @var Collection @ORM\ManyToMany(targetEntity="User", inversedBy="sharedEvents", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_agenda_shares",
     *      joinColumns={
     *      @ORM\JoinColumn(name="evt_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     *      )
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
     * @return guid
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
    {}
}
