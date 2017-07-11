<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * BulletinInfoTitle
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BulletinInfoTitle
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var BulletinInfo
     */
    protected $bulletinInfo;

    /**
     *
     * @var string
     */
    protected $title;

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
    protected $contents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->contents = new ArrayCollection();
    }

    /**
     * Get $id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $bulletinInfo
     *
     * @return BulletinInfo
     */
    public function getBulletinInfo()
    {
        return $this->bulletinInfo;
    }

    /**
     * Set $bulletinInfo
     *
     * @param BulletinInfo $bulletinInfo
     *
     * @return BulletinInfoTitle
     */
    public function setBulletinInfo(BulletinInfo $bulletinInfo)
    {
        $this->bulletinInfo = $bulletinInfo;

        return $this;
    }

    /**
     * Get $title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set $title
     *
     * @param string $title
     *
     * @return BulletinInfoTitle
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     *
     * @return BulletinInfoTitle
     */
    public function setDtCrea(\DateTime $dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get $dtUpdate
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
     *
     * @return BulletinInfoTitle
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add content
     *
     * @param BulletinInfoContent $content
     *
     * @return BulletinInfoTitle
     */
    public function addContent(BulletinInfoContent $content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param BulletinInfoContent $content
     *
     * @return BulletinInfoTitle
     */
    public function removeContent(BulletinInfoContent $content)
    {
        $this->contents->removeElement($content);

        return $this;
    }

    /**
     * Get $contents
     *
     * @return ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set $contents
     *
     * @param Collection $contents
     *
     * @return BulletinInfoTitle
     */
    public function setContents(Collection $contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     */
    public function __clone()
    {
    }
}
