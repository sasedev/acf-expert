<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BulletinInfo
 * @ORM\Table(name="acf_bis")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BulletinInfoRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"num"}, errorPath="num", groups={"num"})
 */
class BulletinInfo
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
     * @var integer @ORM\Column(name="bi_num", type="integer", nullable=false, unique=true)
     *      @Assert\GreaterThan(value=0, groups={"num"})
     */
    protected $num;

    /**
     *
     * @var \DateTime @ORM\Column(name="dtstart", type="date", nullable=false)
     *      @Assert\NotNull(groups={"dtStart"})
     */
    protected $dtStart;

    /**
     *
     * @var string @ORM\Column(name="bi_description", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var integer @ORM\Column(name="nbrclicks", type="bigint", nullable=false)
     */
    protected $nbrClicks;

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
     * @var Collection @ORM\OneToMany(targetEntity="BulletinInfoTitle", mappedBy="bulletinInfo", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"title" = "ASC"})
     */
    protected $titles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->nbrClicks = 0;
        $this->dtStart = new \DateTime('now');
        $this->titles = new ArrayCollection();
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
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     *
     * @param integer $num
     *
     * @return BulletinInfo
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDtStart()
    {
        return $this->dtStart;
    }

    /**
     *
     * @param \DateTime $dtStart
     *
     * @return BulletinInfo
     */
    public function setDtStart(\DateTime $dtStart)
    {
        $this->dtStart = $dtStart;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getNbrClicks()
    {
        return $this->nbrClicks;
    }

    /**
     *
     * @param integer $nbrClicks
     *
     * @return BulletinInfo
     */
    public function setNbrClicks($nbrClicks)
    {
        $this->nbrClicks = $nbrClicks;

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
     * @return BulletinInfo
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @return BulletinInfo
     */
    public function setDtCrea(\DateTime $dtCrea)
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
     * @return BulletinInfo
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add title
     *
     * @param BulletinInfoTitle $title
     *
     * @return BulletinInfo
     */
    public function addTitle(BulletinInfoTitle $title)
    {
        $this->titles[] = $title;

        return $this;
    }

    /**
     * Remove title
     *
     * @param BulletinInfoTitle $title
     *
     * @return BulletinInfo
     */
    public function removeTitle(BulletinInfoTitle $title)
    {
        $this->titles->removeElement($title);

        return $this;
    }

    /**
     * Get titles
     *
     * @return ArrayCollection
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     *
     * @param Collection $titles
     *
     * @return BulletinInfo
     */
    public function setTitles(Collection $titles)
    {
        $this->titles = $titles;

        return $this;
    }

    /**
     *
     */
    public function __clone()
    {

    }
}
