<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * LiasseFolder
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_liassefolders")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\LiasseFolderRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"title", "parent", "company"}, errorPath="title", groups={"title"})
 *         @UniqueEntity(fields={"pageUrlFull"}, errorPath="parent", groups={"parent"})
 */
class LiasseFolder
{

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="liasses", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var LiasseFolder @ORM\ManyToOne(targetEntity="LiasseFolder", inversedBy="childs", cascade={"persist"})
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="parent_id", referencedColumnName="id")})
     */
    protected $parent;

    /**
     *
     * @var string @ORM\Column(name="title", type="text", nullable=false)
     */
    protected $title;

    /**
     *
     * @var string @ORM\Column(name="pageurl_full", type="text", nullable=false)
     *      @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *      @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *      @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     *      }, separator="_", updatable=true, style="camel", fields={"title"})
     */
    protected $pageUrlFull;

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
     * @var Collection @ORM\OneToMany(targetEntity="LiasseFolder", mappedBy="parent",cascade={"persist"})
     *      @ORM\OrderBy({"title" = "ASC"})
     */
    protected $childs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="LiasseDoc", mappedBy="folder",cascade={"persist"})
     *      @ORM\OrderBy({"title" = "ASC"})
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
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;

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
     * @return LiasseFolder
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
     * @return LiasseFolder $this
     */
    public function setPageUrlFull($pageUrlFull)
    {
        $this->pageUrlFull = $pageUrlFull;

        return $this;
    }

    /**
     * Get $parent
     *
     * @return LiasseFolder
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set $parent
     *
     * @param LiasseFolder $parent|null
     *
     * @return LiasseFolder $this
     */
    public function setParent(LiasseFolder $parent = null)
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
     * @return LiasseFolder
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
     * @return LiasseFolder
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add child
     *
     * @param LiasseFolder $child
     *
     * @return LiasseFolder
     */
    public function addChild(LiasseFolder $child)
    {
        $this->childs[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param LiasseFolder $child
     *
     * @return LiasseFolder
     */
    public function removeChild(LiasseFolder $child)
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
     * @return LiasseFolder $this
     */
    public function setChilds(Collection $childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Add doc
     *
     * @param LiasseDoc $doc
     *
     * @return LiasseFolder
     */
    public function addDoc(LiasseDoc $doc)
    {
        $this->docs[] = $doc;

        return $this;
    }

    /**
     * Remove doc
     *
     * @param LiasseDoc $doc
     *
     * @return LiasseFolder
     */
    public function removeDoc(LiasseDoc $doc)
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
     * @return LiasseFolder
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
