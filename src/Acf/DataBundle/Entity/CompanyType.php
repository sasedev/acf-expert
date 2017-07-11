<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * CompanyType
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyType
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
    protected $companies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->companies = new ArrayCollection();
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
     * @return CompanyType
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
     * @return CompanyType
     */
    public function setDtCrea(\DateTime $dtCrea)
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
     * @return CompanyType
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add company
     *
     * @param Company $company
     *
     * @return CompanyType
     */
    public function addCompany(Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param Company $company
     *
     * @return CompanyType
     */
    public function removeCompany(Company $company)
    {
        $this->companies->removeElement($company);

        return $this;
    }

    /**
     * Get companies
     *
     * @return ArrayCollection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     *
     * @param Collection $companies
     *
     * @return CompanyType
     */
    public function setCompanies(Collection $companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     */
    public function __clone()
    {
    }
}
