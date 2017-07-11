<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Docgroupsyst
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Docgroupsyst
{

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
     * @var Docgroupsyst
     */
    protected $parent;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var string
     */
    protected $pageUrlFull;

    /**
     *
     * @var string
     */
    protected $otherInfos;

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
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return Docgroupsyst
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get $parent
     *
     * @return Docgroupsyst
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set $parent
     *
     * @param Docgroupsyst $parent
     *
     * @return Docgroupsyst
     */
    public function setParent(Docgroupsyst $parent = null)
    {
        $this->parent = $parent;

        if (null == $parent) {
            $this->setPageUrlFull($this->getLabel());
        }

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Docgroupsyst
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * @return Docgroupsyst
     */
    public function setPageUrlFull($pageUrlFull)
    {
        $this->pageUrlFull = $pageUrlFull;

        return $this;
    }

    /**
     * Get otherInfos
     *
     * @return string
     */
    public function getOtherInfos()
    {
        return $this->otherInfos;
    }

    /**
     * Set otherInfos
     *
     * @param string $otherInfos
     *
     * @return Docgroupsyst
     */
    public function setOtherInfos($otherInfos)
    {
        $this->otherInfos = $otherInfos;

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
     * @return Docgroupsyst
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
     * @return Docgroupsyst
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add child
     *
     * @param Docgroupsyst $child
     *
     * @return Docgroupsyst
     */
    public function addChild(Docgroupsyst $child)
    {
        $this->childs[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Docgroupsyst $child
     *
     * @return Docgroupsyst
     */
    public function removeChild(Docgroupsyst $child)
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
     * @return Docgroupsyst $this
     */
    public function setChilds(Collection $childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return Docgroupsyst
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;
        $doc->addGroupsyst($this);

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return Docgroupsyst
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);
        $doc->removeGroupsyst($this);

        return $this;
    }

    /**
     * Get docs
     *
     * @return Collection
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     *
     * @param Collection $docs
     *
     * @return Docgroupsyst
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     */
    public function __clone()
    {
        if ($this->id) {
            $docs = $this->getDocs();
            $this->docs = new ArrayCollection();
            foreach ($docs as $doc) {
                $cloneDoc = clone $doc;
                $this->docs->add($cloneDoc);
            }
        }
    }
}
