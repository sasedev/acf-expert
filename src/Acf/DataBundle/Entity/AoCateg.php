<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AoCateg
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="ao_categs")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AoCategRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class AoCateg
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
     * @var string @ORM\Column(name="title", type="text", nullable=false)
     *      @Assert\NotBlank(groups={"title"})
     */
    protected $title;

    /**
     *
     * @var integer @ORM\Column(name="priority", type="bigint", nullable=false)
     */
    protected $priority;

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
     * @var Collection @ORM\OneToMany(targetEntity="AoSubCateg", mappedBy="categ", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"priority" = "ASC", "ref" = "ASC"})
     */
    protected $subCategs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->priority = 100;
        $this->dtCrea = new \DateTime('now');
        $this->subCategs = new ArrayCollection();
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
     * Get $priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set $priority
     *
     * @param integer $priority
     *
     * @return AoCateg
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return AoCateg
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return AoCateg
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
     * @return AoCateg
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add subCateg
     *
     * @param AoSubCateg $subCateg
     *
     * @return AoCateg
     */
    public function addSubCateg(AoSubCateg $subCateg)
    {
        $this->subCategs[] = $subCateg;

        return $this;
    }

    /**
     * Remove subCateg
     *
     * @param AoSubCateg $subCateg
     *
     * @return AoCateg
     */
    public function removeSubCateg(AoSubCateg $subCateg)
    {
        $this->subCategs->removeElement($subCateg);

        return $this;
    }

    /**
     * Get subCategs
     *
     * @return ArrayCollection
     */
    public function getSubCategs()
    {
        return $this->subCategs;
    }

    /**
     *
     * @param Collection $subCategs
     *
     * @return AoCateg
     */
    public function setSubCategs(Collection $subCategs)
    {
        $this->subCategs = $subCategs;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     */
    public function __clone()
    {}
}
