<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_mpayes")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\MPayeRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"month", "year", "company"}, errorPath="month", groups={"month"})
 *         @UniqueEntity(fields={"ref", "company"}, errorPath="ref", groups={"ref"})
 */
class MPaye
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
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="payes", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var string @ORM\Column(name="ref", type="text", nullable=false)
     */
    protected $ref;

    /**
     *
     * @var integer @ORM\Column(name="year", type="integer", nullable=false)
     *      @Assert\GreaterThan(value="0", groups={"year"})
     *      @Assert\LessThan(value="3000", groups={"year"})
     */
    protected $year;

    /**
     *
     * @var integer @ORM\Column(name="month", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceMonthCallback", groups={"physicaltype"})
     */
    protected $month;

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
     * @var Collection @ORM\OneToMany(targetEntity="MSalary", mappedBy="paye", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"matricule" = "ASC"})
     */
    protected $salaries;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Doc", mappedBy="mpayes", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_mpaye_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="mpaye_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $docs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->salaries = new ArrayCollection();
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
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return MPaye
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get string
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
     * @return MPaye
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return MPaye
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return MPaye
     */
    public function setMonth($month)
    {
        $this->month = $month;

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
     * @return MPaye
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
     * @return MPaye
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add salary
     *
     * @param MSalary $salary
     *
     * @return MPaye
     */
    public function addSalary(MSalary $salary)
    {
        $this->salaries[] = $salary;

        return $this;
    }

    /**
     * Remove salary
     *
     * @param MSalary $salary
     *
     * @return MPaye
     */
    public function removeSalary(MSalary $salary)
    {
        $this->salaries->removeElement($salary);

        return $this;
    }

    /**
     * Get salaries
     *
     * @return ArrayCollection
     */
    public function getSalaries()
    {
        return $this->salaries;
    }

    /**
     *
     * @param Collection $salaries
     *
     * @return MPaye
     */
    public function setSalaries(Collection $salaries)
    {
        $this->salaries = $salaries;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return MPaye
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;
        $doc->addMpaye($this);

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return MPaye
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);
        $doc->removeMpaye($this);

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
     * @return MPaye
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     * Choice Form month
     *
     * @return multitype:string
     */
    public static function choiceMonth()
    {
        return array(
            'MPaye.month.choice.' . 1 => 1,
            'MPaye.month.choice.' . 2 => 2,
            'MPaye.month.choice.' . 3 => 3,
            'MPaye.month.choice.' . 4 => 4,
            'MPaye.month.choice.' . 5 => 5,
            'MPaye.month.choice.' . 6 => 6,
            'MPaye.month.choice.' . 7 => 7,
            'MPaye.month.choice.' . 8 => 8,
            'MPaye.month.choice.' . 9 => 9,
            'MPaye.month.choice.' . 10 => 10,
            'MPaye.month.choice.' . 11 => 11,
            'MPaye.month.choice.' . 12 => 12
        );
    }

    /**
     * Choice Validator month
     *
     * @return multitype:integer
     */
    public static function choiceMonthCallback()
    {
        return array(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        );
    }

    /**
     * Generate $ref
     *
     * @return MPaye
     */
    public function generateRef()
    {
        return $this->setRef(sprintf('%04d', $this->getYear()) . '-' . sprintf('%02d', $this->getMonth()));
    }

    /**
     */
    public function __clone()
    {}
}