<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Docgroupsyst
 * @ORM\Table(name="acf_company_docgroupsysts")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\DocgroupsystRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"label", "parent", "company"}, errorPath="label", groups={"label"})
 * @UniqueEntity(fields={"pageUrlFull"}, errorPath="parent", groups={"parent"})
 */
class Docgroupsyst
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
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="docgroupsysts", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var Docgroupsyst @ORM\ManyToOne(targetEntity="Docgroupsyst", inversedBy="childs", cascade={"persist"})
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="parent_id", referencedColumnName="id")})
     */
    protected $parent;

    /**
     *
     * @var string @ORM\Column(name="label", type="text", nullable=false)
     */
    protected $label;

    /**
     *
     * @var string @ORM\Column(name="pageurl_full", type="text", nullable=false)
     *      @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *      @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *      @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     *      }, separator="_", updatable=true, style="camel", fields={"label"})
     */
    protected $pageUrlFull;

    /**
     *
     * @var string @ORM\Column(name="others", type="text", nullable=true)
     */
    protected $otherInfos;

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
     * @var Collection @ORM\OneToMany(targetEntity="Docgroupsyst", mappedBy="parent",cascade={"persist"})
     *      @ORM\OrderBy({"label" = "ASC"})
     */
    protected $childs;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Doc", mappedBy="groupsysts", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupsyst_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      }
     *      )
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
     * @return guid
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
     *
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
