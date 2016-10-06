<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CompanyType
 * @ORM\Table(name="acf_cmp_types")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\CompanyTypeRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"label"}, errorPath="label", groups={"label"})
 */
class CompanyType
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_cmp_types_id_seq", allocationSize=1, initialValue=1)
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
     * @var Collection @ORM\OneToMany(targetEntity="Company", mappedBy="type", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"corporateName" = "ASC"})
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
    {}
}
