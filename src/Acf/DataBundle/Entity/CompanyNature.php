<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * CompanyNature
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyNature
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
    protected $color;

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
        $this->color = '97BBCD';
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
     * @return CompanyNature
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
     * @return CompanyNature
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        $color = str_replace('#', '', $this->color);

        return '#' . $color;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return CompanyNature
     */
    public function setColor($color)
    {
        $color = str_replace('#', '', $color);
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColorRGB()
    {
        $hex = str_replace('#', '', $this->color);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            'r' => $r,
            'g' => $g,
            'b' => $b
        );
        // return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
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
     * @return CompanyNature
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
     * @return CompanyNature
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return CompanyNature
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
     * @return CompanyNature
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
     * @return CompanyNature
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
