<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * BulletinInfo
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BulletinInfo
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $num;

    /**
     *
     * @var \DateTime
     */
    protected $dtStart;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var integer
     */
    protected $nbrClicks;

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
     * @return string
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
     */
    public function __clone()
    {
    }
}
