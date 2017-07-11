<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * BiFolder
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BiFolder
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $pageUrlFull;

    /**
     *
     * @var BiFolder
     */
    protected $parent;

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
    protected $childs;

    /**
     *
     * @var Collection
     */
    protected $docs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->childs = new ArrayCollection();
        $this->docs = new ArrayCollection();
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
     * @return BiFolder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get $pageUrlFull
     *
     * @return string
     */
    public function getPageUrlFull()
    {
        return $this->pageUrlFull;
    }

    /**
     * Set $pageUrlFull
     *
     * @param string $pageUrlFull
     *
     * @return BiFolder $this
     */
    public function setPageUrlFull($pageUrlFull)
    {
        $this->pageUrlFull = $pageUrlFull;

        return $this;
    }

    /**
     * Get $parent
     *
     * @return BiFolder
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set $parent
     *
     * @param BiFolder $parent|null
     *
     * @return BiFolder $this
     */
    public function setParent(BiFolder $parent = null)
    {
        $this->parent = $parent;

        if (null == $parent) {
            $this->setPageUrlFull($this->getTitle());
        }

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
     * @return BiFolder
     */
    public function setDtCrea(\DateTime $dtCrea = null)
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
     * @return BiFolder
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add child
     *
     * @param BiFolder $child
     *
     * @return BiFolder
     */
    public function addChild(BiFolder $child)
    {
        $this->childs[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param BiFolder $child
     *
     * @return BiFolder
     */
    public function removeChild(BiFolder $child)
    {
        $this->childs->removeElement($child);

        return $this;
    }

    /**
     * Get childs
     *
     * @return ArrayCollection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set $childs
     *
     * @param Collection $childs
     *
     * @return BiFolder $this
     */
    public function setChilds(Collection $childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Add doc
     *
     * @param BiDoc $doc
     *
     * @return BiFolder
     */
    public function addDoc(BiDoc $doc)
    {
        $this->docs[] = $doc;

        return $this;
    }

    /**
     * Remove doc
     *
     * @param BiDoc $doc
     *
     * @return BiFolder
     */
    public function removeDoc(BiDoc $doc)
    {
        $this->docs->removeElement($doc);

        return $this;
    }

    /**
     * Get docs
     *
     * @return ArrayCollection
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     *
     * @param Collection $docs
     *
     * @return BiFolder
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' ' . $this->getTitle();
    }

    /**
     */
    public function __clone()
    {
    }
}
