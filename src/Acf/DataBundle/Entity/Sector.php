<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Sector
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Sector
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
     *
     * @var Collection
     */
    protected $relations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->companies = new ArrayCollection();
        $this->relations = new ArrayCollection();
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
     * @return Sector
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
     * @return Sector
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
     * @return Sector
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add company
     *
     * @param Company $company
     *
     * @return Sector
     */
    public function addCompany(Company $company)
    {
        $this->companies[] = $company;
        $company->addSector($this);

        return $this;
    }

    /**
     * Remove company
     *
     * @param Company $company
     *
     * @return Sector
     */
    public function removeCompany(Company $company)
    {
        $this->companies->removeElement($company);
        $company->removeSector($this);

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
     * @return Sector
     */
    public function setCompanies(Collection $companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     * Add relation
     *
     * @param Relation $relation
     *
     * @return Sector
     */
    public function addRelation(Relation $relation)
    {
        $this->relations[] = $relation;
        $relation->addSector($this);

        return $this;
    }

    /**
     * Remove relation
     *
     * @param Relation $relation
     *
     * @return Sector
     */
    public function removeRelation(Relation $relation)
    {
        $this->relations->removeElement($relation);
        $relation->removeSector($this);

        return $this;
    }

    /**
     * Get relations
     *
     * @return ArrayCollection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     *
     * @param Collection $relations
     *
     * @return Sector
     */
    public function setRelations(Collection $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     */
    public function __clone()
    {
    }
}
