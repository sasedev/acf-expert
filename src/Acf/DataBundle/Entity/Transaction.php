<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Transaction
 *
 * @author sasedev <seif.salah@gmail.com>
 */
abstract class Transaction
{

    /**
     *
     * @var string
     */
    const TTYPE_SALE = '1';

    /**
     *
     * @var string
     */
    const TTYPE_BUY = '2';

    /**
     *
     * @var integer
     */
    const PTYPE_NA = 0;

    /**
     *
     * @var integer
     */
    const PTYPE_CHECK = 1;

    /**
     *
     * @var integer
     */
    const PTYPE_VRT = 2;

    /**
     *
     * @var integer
     */
    const PTYPE_CAISSE = 3;

    /**
     *
     * @var integer
     */
    const PTYPE_TRAIT = 4;

    /**
     *
     * @var integer
     */
    const PTYPE_CB = 5;

    /**
     *
     * @var integer
     */
    const STATUS_PENDING = 0;

    /**
     *
     * @var integer
     */
    const STATUS_DONE = 1;

    /**
     *
     * @var integer
     */
    const STATUS_CANCELLED = 10;

    /**
     *
     * @var integer
     */
    const VALIDATED_NO = 1;

    /**
     *
     * @var integer
     */
    const VALIDATED_YES = 2;

    /**
     *
     * @var integer
     */
    const VI_0 = 0;

    /**
     *
     * @var integer
     */
    const VI_6 = 6;

    /**
     *
     * @var integer
     */
    const VI_12 = 12;

    /**
     *
     * @var integer
     */
    const VI_18 = 18;

    /**
     *
     * @var integer
     */
    const R_0 = 0;

    /**
     *
     * @var integer
     */
    const R_1 = 1;

    /**
     *
     * @var integer
     */
    const R_2 = 2;

    /**
     *
     * @var integer
     */
    const R_3 = 3;

    /**
     *
     * @var integer
     */
    const R_4 = 4;

    /**
     *
     * @var integer
     */
    const R_5 = 5;

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var MonthlyBalance
     */
    protected $monthlyBalance;

    /**
     *
     * @var integer
     */
    protected $number;

    /**
     *
     * @var \DateTime
     */
    protected $dtActivation;

    /**
     *
     * @var string
     */
    protected $bill;

    /**
     *
     * @var Relation
     */
    protected $relation;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var string
     */
    protected $devise;

    /**
     *
     * @var float
     */
    protected $conversionRate;

    /**
     *
     * @var float
     */
    protected $vat;

    /**
     *
     * @var float
     */
    protected $vatDevise;

    /**
     *
     * @var float
     */
    protected $stamp;

    /**
     *
     * @var float
     */
    protected $stampDevise;

    /**
     *
     * @var float
     */
    protected $balanceTtc;

    /**
     *
     * @var float
     */
    protected $balanceTtcDevise;

    /**
     *
     * @var float
     */
    protected $balanceNet;

    /**
     *
     * @var float
     */
    protected $balanceNetDevise;

    /**
     *
     * @var integer
     */
    protected $vatInfo;

    /**
     *
     * @var integer
     */
    protected $regime;

    /**
     *
     * @var Withholding
     */
    protected $withholding;

    /**
     *
     * @var integer
     */
    protected $paymentType;

    /**
     *
     * @var \DateTime
     */
    protected $dtPayment;

    /**
     *
     * @var Account
     */
    protected $account;

    /**
     *
     * @var CompanyNature
     */
    protected $nature;

    /**
     *
     * @var integer
     */
    protected $transactionStatus;

    /**
     *
     * @var integer
     */
    protected $validated;

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
    protected $docs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
        $this->devise = 'TND';
        $this->conversionRate = 1;
        $this->vat = 0;
        $this->vatDevise = 0;
        $this->stamp = 0;
        $this->stampDevise = 0;
        $this->balanceTtc = 0;
        $this->balanceTtcDevise = 0;
        $this->balanceNet = 0;
        $this->balanceNetDevise = 0;
        $this->vatInfo = self::VI_0;
        $this->regime = self::R_0;
        $this->paymentType = self::PTYPE_NA;
        $this->transactionStatus = self::STATUS_PENDING;
        $this->validated = self::VALIDATED_NO;
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
     * Get monthlyBalance
     *
     * @return MonthlyBalance
     */
    public function getMonthlyBalance()
    {
        return $this->monthlyBalance;
    }

    /**
     * Set monthlyBalance
     *
     * @param MonthlyBalance $monthlyBalance
     *
     * @return Transaction
     */
    public function setMonthlyBalance(MonthlyBalance $monthlyBalance = null)
    {
        $this->monthlyBalance = $monthlyBalance;

        return $this;
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->getMonthlyBalance()->getCompany();
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
     * @return Transaction
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
     * Get dtActivation
     *
     * @return \DateTime
     */
    public function getDtActivation()
    {
        return $this->dtActivation;
    }

    /**
     * Set dtActivation
     *
     * @param \DateTime $dtActivation
     *
     * @return Transaction
     */
    public function setDtActivation(\DateTime $dtActivation)
    {
        $this->dtActivation = $dtActivation;

        return $this;
    }

    /**
     * Get bill
     *
     * @return string
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set bill
     *
     * @param string $bill
     *
     * @return Transaction
     */
    public function setBill($bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get relation
     *
     * @return Relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set relation
     *
     * @param Relation $relation
     *
     * @return Transaction
     */
    public function setRelation(Relation $relation = null)
    {
        $this->relation = $relation;

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
     * @return Transaction
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get string
     *
     * @return string
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set $devise
     *
     * @param string $devise
     *
     * @return Transaction
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * Set $conversionRate
     *
     * @param float $conversionRate
     *
     * @return Transaction
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;

        return $this;
    }

    /**
     * Get vat
     *
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set vat
     *
     * @param float $vat
     *
     * @return Transaction
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getVatDevise()
    {
        return $this->vatDevise;
    }

    /**
     * Set $vatDevise
     *
     * @param float $vatDevise
     *
     * @return Transaction
     */
    public function setVatDevise($vatDevise)
    {
        $this->vatDevise = $vatDevise;

        return $this;
    }

    /**
     * Get stamp
     *
     * @return float
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * Set stamp
     *
     * @param float $stamp
     *
     * @return Transaction
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getStampDevise()
    {
        return $this->stampDevise;
    }

    /**
     * Set $stampDevise
     *
     * @param float $stampDevise
     *
     * @return Transaction
     */
    public function setStampDevise($stampDevise)
    {
        $this->stampDevise = $stampDevise;

        return $this;
    }

    /**
     * Get balanceTtc
     *
     * @return float
     */
    public function getBalanceTtc()
    {
        return $this->balanceTtc;
    }

    /**
     * Set balanceTtc
     *
     * @param float $balanceTtc
     *
     * @return Transaction
     */
    public function setBalanceTtc($balanceTtc)
    {
        $this->balanceTtc = $balanceTtc;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getBalanceTtcDevise()
    {
        return $this->balanceTtcDevise;
    }

    /**
     * Set $balanceTtcDevise
     *
     * @param float $balanceTtcDevise
     *
     * @return Transaction
     */
    public function setBalanceTtcDevise($balanceTtcDevise)
    {
        $this->balanceTtcDevise = $balanceTtcDevise;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getBalanceNet()
    {
        return $this->balanceNet;
    }

    /**
     * Set $balanceNet
     *
     * @param float $balanceNet
     *
     * @return Transaction $this
     */
    public function setBalanceNet($balanceNet)
    {
        $this->balanceNet = $balanceNet;

        return $this;
    }

    /**
     * Get float
     *
     * @return float
     */
    public function getBalanceNetDevise()
    {
        return $this->balanceNetDevise;
    }

    /**
     * Set $balanceNetDevise
     *
     * @param float $balanceNetDevise
     *
     * @return Transaction
     */
    public function setBalanceNetDevise($balanceNetDevise)
    {
        $this->balanceNetDevise = $balanceNetDevise;

        return $this;
    }

    /**
     * Get integer
     *
     * @return integer
     */
    public function getVatInfo()
    {
        return $this->vatInfo;
    }

    /**
     * Set $vatInfo
     *
     * @param integer $vatInfo
     *
     * @return Transaction
     */
    public function setVatInfo($vatInfo)
    {
        $this->vatInfo = $vatInfo;

        return $this;
    }

    /**
     * Get integer
     *
     * @return integer
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * Set $regime
     *
     * @param integer $regime
     *
     * @return Transaction
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;

        return $this;
    }

    /**
     * Get withholding
     *
     * @return Withholding
     */
    public function getWithholding()
    {
        return $this->withholding;
    }

    /**
     * Set withholding
     *
     * @param Withholding $withholding
     *
     * @return Transaction
     */
    public function setWithholding(Withholding $withholding = null)
    {
        $this->withholding = $withholding;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return integer
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set paymentType
     *
     * @param integer $paymentType
     *
     * @return Transaction
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get dtPayment
     *
     * @return \DateTime
     */
    public function getDtPayment()
    {
        return $this->dtPayment;
    }

    /**
     * Set dtPayment
     *
     * @param \DateTime $dtPayment
     *
     * @return Transaction
     */
    public function setDtPayment(\DateTime $dtPayment = null)
    {
        $this->dtPayment = $dtPayment;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Transaction
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     *
     * @return CompanyNature
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     *
     * @param CompanyNature $companyNature
     *
     * @return Transaction
     */
    public function setNature(CompanyNature $companyNature = null)
    {
        $this->nature = $companyNature;

        return $this;
    }

    /**
     * Get transactionStatus
     *
     * @return integer
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Set transactionStatus
     *
     * @param integer $transactionStatus
     *
     * @return Transaction
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     *
     * @param integer $validated
     *
     * @return Transaction
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Set otherInfos
     *
     * @param string $otherInfos
     *
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add doc
     *
     * @param Doc $doc
     *
     * @return Transaction
     */
    public function addDoc(Doc $doc)
    {
        $this->docs[] = $doc;
        $doc->addTransaction($this);

        return $this;
    }

    /**
     * Remove doc
     *
     * @param Doc $doc
     *
     * @return Transaction
     */
    public function removeDoc(Doc $doc)
    {
        $this->docs->removeElement($doc);
        $doc->removeTransaction($this);

        return $this;
    }

    /**
     * Get docs
     *
     * @return Collection
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     *
     * @param Collection $docs
     *
     * @return Transaction
     */
    public function setDocs(Collection $docs)
    {
        $this->docs = $docs;

        return $this;
    }

    /**
     * Choice Form vatInfo
     *
     * @return multitype:string
     */
    public static function choiceVatInfo()
    {
        return array(
            'Transaction.vatInfo.choice.' . self::VI_0 => self::VI_0,
            'Transaction.vatInfo.choice.' . self::VI_6 => self::VI_6,
            'Transaction.vatInfo.choice.' . self::VI_12 => self::VI_12,
            'Transaction.vatInfo.choice.' . self::VI_18 => self::VI_18
        );
    }

    /**
     * Choice Validator vatInfo
     *
     * @return multitype:integer
     */
    public static function choiceVatInfoCallback()
    {
        return array(
            self::VI_0,
            self::VI_6,
            self::VI_12,
            self::VI_18
        );
    }

    /**
     * Choice Form regime
     *
     * @return multitype:string
     */
    public static function choiceRegime()
    {
        return array(
            'Transaction.regime.choice.' . self::R_0 => self::R_0,
            'Transaction.regime.choice.' . self::R_1 => self::R_1,
            'Transaction.regime.choice.' . self::R_2 => self::R_2,
            'Transaction.regime.choice.' . self::R_3 => self::R_3,
            'Transaction.regime.choice.' . self::R_4 => self::R_4,
            'Transaction.regime.choice.' . self::R_5 => self::R_5
        );
    }

    /**
     * Choice Validator regime
     *
     * @return multitype:integer
     */
    public static function choiceRegimeCallback()
    {
        return array(
            self::R_0,
            self::R_1,
            self::R_2,
            self::R_3,
            self::R_4,
            self::R_5
        );
    }

    /**
     * Choice Form paymentType
     *
     * @return multitype:string
     */
    public static function choicePaymentType()
    {
        return array(
            'Transaction.paymentType.choice.' . self::PTYPE_NA => self::PTYPE_NA,
            'Transaction.paymentType.choice.' . self::PTYPE_CHECK => self::PTYPE_CHECK,
            'Transaction.paymentType.choice.' . self::PTYPE_VRT => self::PTYPE_VRT,
            'Transaction.paymentType.choice.' . self::PTYPE_CAISSE => self::PTYPE_CAISSE,
            'Transaction.paymentType.choice.' . self::PTYPE_TRAIT => self::PTYPE_TRAIT,
            'Transaction.paymentType.choice.' . self::PTYPE_CB => self::PTYPE_CB
        );
    }

    /**
     * Choice Validator paymentType
     *
     * @return multitype:integer
     */
    public static function choicePaymentTypeCallback()
    {
        return array(
            self::PTYPE_NA,
            self::PTYPE_CHECK,
            self::PTYPE_VRT,
            self::PTYPE_CAISSE,
            self::PTYPE_TRAIT,
            self::PTYPE_CB
        );
    }

    /**
     * Choice Form physicaltype
     *
     * @return multitype:string
     */
    public static function choiceTransactionStatus()
    {
        return array(
            'Transaction.transactionStatus.choice.' . self::STATUS_PENDING => self::STATUS_PENDING,
            'Transaction.transactionStatus.choice.' . self::STATUS_DONE => self::STATUS_DONE,
            'Transaction.transactionStatus.choice.' . self::STATUS_CANCELLED => self::STATUS_CANCELLED
        );
    }

    /**
     * Choice Validator sexe
     *
     * @return multitype:integer
     */
    public static function choiceTransactionStatusCallback()
    {
        return array(
            self::STATUS_PENDING,
            self::STATUS_DONE,
            self::STATUS_CANCELLED
        );
    }

    /**
     * Choice Form physicaltype
     *
     * @return multitype:string
     */
    public static function choiceValidated()
    {
        return array(
            'Transaction.validated.choice.' . self::VALIDATED_NO => self::VALIDATED_NO,
            'Transaction.validated.choice.' . self::VALIDATED_YES => self::VALIDATED_YES
        );
    }

    /**
     * Choice Validator sexe
     *
     * @return multitype:integer
     */
    public static function choiceValidatedCallback()
    {
        return array(
            self::VALIDATED_NO,
            self::VALIDATED_YES
        );
    }

    /**
     */
    public function __clone()
    {
    }
}
