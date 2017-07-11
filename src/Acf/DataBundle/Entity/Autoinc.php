<?php
namespace Acf\DataBundle\Entity;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Autoinc
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var integer
     */
    protected $value;

    /**
     *
     * @var integer
     */
    protected $count;

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
     * @return string
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
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set $value
     *
     * @param integer $value
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
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set $count
     *
     * @param integer $count
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
