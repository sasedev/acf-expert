<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Account
 *
 * @author sasedev <seif.salah@gmail.com>
 */
abstract class Account
{

    /**
     *
     * @var string
     */
    const TYPE_BANK = '1';

    /**
     *
     * @var string
     */
    const TYPE_FUND = '2';

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
     * @var string
     */
    protected $agency;

    /**
     *
     * @var string
     */
    protected $rib;

    /**
     *
     * @var string
     */
    protected $contact;

    /**
     *
     * @var string
     */
    protected $tel;

    /**
     *
     * @var string
     */
    protected $fax;

    /**
     *
     * @var string
     */
    protected $email;

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
     * Set company
     *
     * @param Company $company
     *
     * @return Account
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
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
     * Set label
     *
     * @param string $label
     *
     * @return Account
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * Set number
     *
     * @param integer $number
     *
     * @return Account
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * Get numberFormated
     *
     * @return integer
     */
    public function getNumberFormated()
    {
        return sprintf('%09d', $this->getNumber());
    }

    /**
     * Set agency
     *
     * @param string $agency
     *
     * @return Account
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return string
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Account
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     *
     * @param string $contact
     *
     * @return Account
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     *
     * @param string $tel
     *
     * @return Account
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     *
     * @param string $fax
     *
     * @return Account
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param string $email
     *
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set otherInfos
     *
     * @param string $otherInfos
     *
     * @return Account
     */
    public function setOtherInfos($otherInfos)
    {
        $this->otherInfos = $otherInfos;

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
     * Set dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Account
     */
    public function setDtCrea($dtCrea)
    {
        $this->dtCrea = $dtCrea;

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
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Account
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

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
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return Account
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
     * @return Account
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
     * @return Account
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
     * @return Account
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;
        $doc->addAccount($this);

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return Account
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);
        $doc->removeAccount($this);

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
     * @return Account
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
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
