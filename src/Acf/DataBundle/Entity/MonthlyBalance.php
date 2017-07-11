<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * MonthlyBalance
 *
 * @author sasedev <seif.salah@gmail.com>
 */
abstract class MonthlyBalance
{

    /**
     *
     * @var string
     */
    const TYPE_SALE = '1';

    /**
     *
     * @var string
     */
    const TYPE_PURCHASE = '2';

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
    protected $ref;

    /**
     *
     * @var integer
     */
    protected $year;

    /**
     *
     * @var integer
     */
    protected $month;

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
     *
     * @var Collection
     */
    protected $transactions;

    /**
     *
     * @var Collection
     */
    protected $docs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->count = 1;
        $this->transactions = new ArrayCollection();
        $this->docs = new ArrayCollection();
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return MonthlyBalance
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Update count
     *
     * @return MonthlyBalance
     */
    public function updateCount()
    {
        $this->count = $this->count + 1;

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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
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
     * @return MonthlyBalance
     */
    public function setTransactions(Collection $transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return MonthlyBalance
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;
        $doc->addMonthlyBalance($this);

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return MonthlyBalance
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);
        $doc->removeMonthlyBalance($this);

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
     * @return MonthlyBalance
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
            'MonthlyBalance.month.choice.' . 1 => 1,
            'MonthlyBalance.month.choice.' . 2 => 2,
            'MonthlyBalance.month.choice.' . 3 => 3,
            'MonthlyBalance.month.choice.' . 4 => 4,
            'MonthlyBalance.month.choice.' . 5 => 5,
            'MonthlyBalance.month.choice.' . 6 => 6,
            'MonthlyBalance.month.choice.' . 7 => 7,
            'MonthlyBalance.month.choice.' . 8 => 8,
            'MonthlyBalance.month.choice.' . 9 => 9,
            'MonthlyBalance.month.choice.' . 10 => 10,
            'MonthlyBalance.month.choice.' . 11 => 11,
            'MonthlyBalance.month.choice.' . 12 => 12
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
     */
    public function __clone()
    {
        if ($this->id) {
            $docs = $this->getDocs();
            $this->docs = new ArrayCollection();
            foreach ($docs as $doc) {
                $cloneDoc = clone $doc;
                $this->docs->add($cloneDoc);
            }
        }
    }
}
