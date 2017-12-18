<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AoSubCateg
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="ao_subcategs")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AoSubCategRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"ref"}, errorPath="ref", groups={"ref"})
 */
class AoSubCateg
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
     * @var string @ORM\Column(name="ref", type="text", nullable=false)
     *      @Assert\NotBlank(groups={"ref"})
     */
    protected $ref;

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
     * @var AoCateg @ORM\ManyToOne(targetEntity="AoCateg", inversedBy="subCategs", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="categ_id", referencedColumnName="id")
     *      })
     */
    protected $categ;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="AoAdvertisement", mappedBy="grp", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"ref" = "ASC"})
     */
    protected $advertisements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->priority = 100;
        $this->dtCrea = new \DateTime('now');
        $this->advertisements = new ArrayCollection();
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
     * Get $ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set $ref
     *
     * @param string $ref
     *
     * @return AoSubCateg
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

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
     * @return AoSubCateg
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * @return AoSubCateg
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

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
     * @return AoSubCateg
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
     * @return AoSubCateg
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get $categ
     *
     * @return AoCateg
     */
    public function getCateg()
    {
        return $this->categ;
    }

    /**
     * Set $categ
     *
     * @param AoCateg $categ
     *
     * @return AoSubCateg
     */
    public function setCateg(AoCateg $categ)
    {
        $this->categ = $categ;

        return $this;
    }

    /**
     * Add advertisement
     *
     * @param AoAdvertisement $advertisement
     *
     * @return AoSubCateg
     */
    public function addAdvertisement(AoAdvertisement $advertisement)
    {
        $this->advertisements[] = $advertisement;

        return $this;
    }

    /**
     * Remove advertisement
     *
     * @param AoAdvertisement $advertisement
     *
     * @return AoSubCateg
     */
    public function removeAdvertisement(AoAdvertisement $advertisement)
    {
        $this->advertisements->removeElement($advertisement);

        return $this;
    }

    /**
     * Get advertisements
     *
     * @return ArrayCollection
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     *
     * @param Collection $advertisements
     *
     * @return AoSubCateg
     */
    public function setAdvertisements(Collection $advertisements)
    {
        $this->advertisements = $advertisements;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRef() . ' : ' . $this->getTitle();
    }

    /**
     */
    public function __clone()
    {}
}
