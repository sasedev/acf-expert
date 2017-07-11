<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Job
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Job
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $label;

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
     */
    public function __clone()
    {
    }
}