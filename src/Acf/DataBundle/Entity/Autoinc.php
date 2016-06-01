<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_autoincs")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\AutoincRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"name"}, errorPath="name", groups={"name"})
 */
class Autoinc
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
     * @var string @ORM\Column(name="name", type="text", nullable=false)
     *      @Assert\NotBlank(groups={"name"})
     */
    protected $name;

    /**
     *
     * @var string @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var bigint @ORM\Column(name="val", type="bigint", nullable=false)
     */
    protected $value;

    /**
     *
     * @var bigint @ORM\Column(name="cnt", type="bigint", nullable=false)
     */
    protected $count;

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
     * Constructor
     *
     * @param integer $value
     * @param integer $count
     */
    public function __construct($value = 1, $count = 0)
    {
        $this->dtCrea = new \DateTime('now');
        $this->value = $value;
        $this->count = $count;
    }

    /**
     * Get $id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set $name
     *
     * @param string $name
     *
     * @return Autoinc
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get $description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set $description
     *
     * @param string $description
     *
     * @return Autoinc
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get $value
     *
     * @return bigint
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set $value
     *
     * @param bigint $value
     *
     * @return Autoinc
     */
    private function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get $count
     *
     * @return bigint
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set $count
     *
     * @param bigint $count
     *
     * @return Autoinc
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get $dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set $dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Autoinc
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get $dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set $dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Autoinc
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }
}
