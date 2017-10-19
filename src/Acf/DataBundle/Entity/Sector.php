<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Sector
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_cmp_sectors")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\SectorRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"label"}, errorPath="label", groups={"label"})
 */
class Sector
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="bigint", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_cmp_sectors_id_seq", allocationSize=1, initialValue=1)
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
     * @var Collection @ORM\ManyToMany(targetEntity="Company", mappedBy="sectors", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_company_sectors",
     *      joinColumns={
     *      @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $companies;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Relation", mappedBy="sectors", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_relation_sectors",
     *      joinColumns={
     *      @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="relation_id", referencedColumnName="id")
     *      }
     *      )
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
    {}
}
