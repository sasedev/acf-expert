<?php
namespace Acf\DataBundle\Entity;

/**
 * Phone
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Phone
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var Company
     */
    protected $company;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var string
     */
    protected $phone;

    /**
     *
     * @var string
     */
    protected $contact;

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
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
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
     * @return Phone
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
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
     * @return Phone
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Phone
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

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
     * @return Phone
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
     * @return Phone
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     */
    public function __clone()
    {
    }
}
