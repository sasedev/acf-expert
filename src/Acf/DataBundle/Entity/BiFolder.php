<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_bifolders")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BiFolderRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"title", "parent"}, errorPath="title", groups={"title"})
 */
class BiFolder
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
     * @var BiFolder @ORM\ManyToOne(targetEntity="BiFolder", inversedBy="childs", cascade={"persist"})
     *      @ORM\JoinColumns({@ORM\JoinColumn(name="parent_id", referencedColumnName="id")})
     */
    protected $parent;

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
     * @var Collection @ORM\OneToMany(targetEntity="BiFolder", mappedBy="parent",cascade={"persist"})
     *      @ORM\OrderBy({"title" = "ASC"})
     */
    protected $childs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="BiDoc", mappedBy="folder", cascade={"persist", "remove"})
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
     * @return guid
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
    {}
}
