<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Withholding
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Withholding
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
     * @var integer
     */
    protected $number;

    /**
     *
     * @var float
     */
    protected $value;

    /**
     *
     * @var string
     */
    protected $otherInfos;

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
    protected $transactions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->transactions = new ArrayCollection();
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
     * @return Withholding
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
     * @return Withholding
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Withholding
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get numberFormated
     *
     * @return integer
     */
    public function getNumberFormated()
    {
        return sprintf('%09d', $this->getNumber());
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return Withholding
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get otherInfos
     *
     * @return string
     */
    public function getOtherInfos()
    {
        return $this->otherInfos;
    }

    /**
     * Set otherInfos
     *
     * @param string $otherInfos
     *
     * @return Withholding
     */
    public function setOtherInfos($otherInfos)
    {
        $this->otherInfos = $otherInfos;

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
     * @return Withholding
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
     * @return Withholding
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return Withholding
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param Transaction $transaction
     *
     * @return Withholding
     */
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);

        return $this;
    }

    /**
     * Get transactions
     *
     * @return ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     *
     * @param Collection $transactions
     *
     * @return Withholding
     */
    public function setTransactions(Collection $transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     */
    public function __clone()
    {
    }
}
