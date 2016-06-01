<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Job
 * @ORM\Table(name="acf_jobs")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\JobRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"label"}, errorPath="label", groups={"label"})
 */
class Job
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="bigint", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_jobs_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="label", type="text", nullable=false)
     */
    protected $label;

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
     * @var Collection @ORM\OneToMany(targetEntity="CompanyFrame", mappedBy="job", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"lastName" = "ASC", "firstName" = "ASC"})
     */
    protected $companyFrames;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->companyFrames = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return Job
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * @return Job
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
     * @return Job
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add companyFrames
     *
     * @param CompanyFrame $companyFrame
     *
     * @return Job
     */
    public function addCompanyFrame(CompanyFrame $companyFrame)
    {
        $this->companyFrames[] = $companyFrame;

        return $this;
    }

    /**
     * Remove companyFrame
     *
     * @param CompanyFrame $companyFrame
     *
     * @return Job
     */
    public function removeCompanyFrame(CompanyFrame $companyFrame)
    {
        $this->companyFrames->removeElement($companyFrame);

        return $this;
    }

    /**
     * Get companyFrames
     *
     * @return ArrayCollection
     */
    public function getCompanyFrames()
    {
        return $this->companyFrames;
    }

    /**
     *
     * @param Collection $companyFrames
     *
     * @return Job
     */
    public function setCompanyFrames(Collection $companyFrames)
    {
        $this->companyFrames = $companyFrames;

        return $this;
    }

    /**
     *
     */
    public function __clone()
    {

    }
}