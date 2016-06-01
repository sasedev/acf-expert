<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Doc
 * @ORM\Table(name="acf_company_docs")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\DocRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Doc
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
     * @var string @ORM\Column(name="filename", type="text", nullable=false)
     *      @Assert\File(maxSize='20480k', groups={"fileName"})
     */
    protected $fileName;

    /**
     *
     * @var integer @ORM\Column(name="filesize", type="bigint", nullable=false)
     */
    protected $size;

    /**
     *
     * @var string @ORM\Column(name="filemimetype", type="text", nullable=false)
     */
    protected $mimeType;

    /**
     *
     * @var string @ORM\Column(name="filemd5", type="text", nullable=false)
     */
    protected $md5;

    /**
     *
     * @var string @ORM\Column(name="fileoname", type="text", nullable=false)
     */
    protected $originalName;

    /**
     *
     * @var string @ORM\Column(name="filedesc", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var integer @ORM\Column(name="filedls", type="bigint", nullable=false)
     */
    protected $nbrDownloads;

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
     * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="docs",cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      })
     */
    protected $company;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroup", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_group_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groups;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupcomptable", inversedBy="docs",
     *      cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupcomptable_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groupcomptables;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupfiscal", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupfiscal_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groupfiscals;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupperso", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupperso_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $grouppersos;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupsyst", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupsyst_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groupsysts;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupbank", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupbank_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groupbanks;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Docgroupaudit", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_groupaudit_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $groupaudits;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Account", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_account_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $accounts;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Relation", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_relation_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="relation_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $relations;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="MonthlyBalance", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_mbalance_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="mbalance_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $monthlyBalances;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Transaction", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_transaction_docs",
     *      joinColumns={
     *      @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $transactions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->size = 0;
        $this->nbrDownloads = 0;
        $this->dtCrea = new \DateTime('now');
        $this->groups = new ArrayCollection();
        $this->groupcomptables = new ArrayCollection();
        $this->groupfiscals = new ArrayCollection();
        $this->grouppersos = new ArrayCollection();
        $this->groupsysts = new ArrayCollection();
        $this->groupbanks = new ArrayCollection();
        $this->groupaudits = new ArrayCollection();
        $this->accounts = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->monthlyBalances = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Doc
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Doc
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return Doc
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set md5
     *
     * @param string $md5
     *
     * @return Doc
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     *
     * @return Doc
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Doc
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get nbrDownloads
     *
     * @return integer
     */
    public function getNbrDownloads()
    {
        return $this->nbrDownloads;
    }

    /**
     * Set nbrDownloads
     *
     * @param integer $nbrDownloads
     *
     * @return Doc
     */
    public function setNbrDownloads($nbrDownloads)
    {
        $this->nbrDownloads = $nbrDownloads;

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
     * @return Doc
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
     * @return Doc
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

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
     * Set company
     *
     * @param Company $company
     *
     * @return Doc
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Add group
     *
     * @param Docgroup $group
     *
     * @return Doc
     */
    public function addGroup(Docgroup $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param Docgroup $group
     *
     * @return Doc
     */
    public function removeGroup(Docgroup $group)
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * Get groups
     *
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     *
     * @param Collection $groups
     *
     * @return Doc
     */
    public function setGroups(Collection $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Add groupcomptable
     *
     * @param Docgroupcomptable $groupcomptable
     *
     * @return Doc
     */
    public function addGroupcomptable(Docgroupcomptable $groupcomptable)
    {
        $this->groupcomptables[] = $groupcomptable;

        return $this;
    }

    /**
     * Remove groupcomptable
     *
     * @param Docgroupcomptable $groupcomptable
     *
     * @return Doc
     */
    public function removeGroupcomptable(Docgroupcomptable $groupcomptable)
    {
        $this->groupcomptables->removeElement($groupcomptable);

        return $this;
    }

    /**
     * Get groupcomptables
     *
     * @return ArrayCollection
     */
    public function getGroupcomptables()
    {
        return $this->groupcomptables;
    }

    /**
     *
     * @param Collection $groupcomptables
     *
     * @return Doc
     */
    public function setGroupcomptables(Collection $groupcomptables)
    {
        $this->groupcomptables = $groupcomptables;

        return $this;
    }

    /**
     * Add groupfiscal
     *
     * @param Docgroupfiscal $groupfiscal
     *
     * @return Doc
     */
    public function addGroupfiscal(Docgroupfiscal $groupfiscal)
    {
        $this->groupfiscals[] = $groupfiscal;

        return $this;
    }

    /**
     * Remove groupfiscal
     *
     * @param Docgroupfiscal $groupfiscal
     *
     * @return Doc
     */
    public function removeGroupfiscal(Docgroupfiscal $groupfiscal)
    {
        $this->groupfiscals->removeElement($groupfiscal);

        return $this;
    }

    /**
     * Get groupfiscals
     *
     * @return ArrayCollection
     */
    public function getGroupfiscals()
    {
        return $this->groupfiscals;
    }

    /**
     *
     * @param Collection $groupfiscals
     *
     * @return Doc
     */
    public function setGroupfiscals(Collection $groupfiscals)
    {
        $this->groupfiscals = $groupfiscals;

        return $this;
    }

    /**
     * Add groupperso
     *
     * @param Docgroupperso $groupperso
     *
     * @return Doc
     */
    public function addGroupperso(Docgroupperso $groupperso)
    {
        $this->grouppersos[] = $groupperso;

        return $this;
    }

    /**
     * Remove groupperso
     *
     * @param Docgroupperso $groupperso
     *
     * @return Doc
     */
    public function removeGroupperso(Docgroupperso $groupperso)
    {
        $this->grouppersos->removeElement($groupperso);

        return $this;
    }

    /**
     * Get grouppersos
     *
     * @return ArrayCollection
     */
    public function getGrouppersos()
    {
        return $this->grouppersos;
    }

    /**
     *
     * @param Collection $grouppersos
     *
     * @return Doc
     */
    public function setGrouppersos(Collection $grouppersos)
    {
        $this->grouppersos = $grouppersos;

        return $this;
    }

    /**
     * Add groupsyst
     *
     * @param Docgroupsyst $groupsyst
     *
     * @return Doc
     */
    public function addGroupsyst(Docgroupsyst $groupsyst)
    {
        $this->groupsysts[] = $groupsyst;

        return $this;
    }

    /**
     * Remove groupsyst
     *
     * @param Docgroupsyst $groupsyst
     *
     * @return Doc
     */
    public function removeGroupsyst(Docgroupsyst $groupsyst)
    {
        $this->groupsysts->removeElement($groupsyst);

        return $this;
    }

    /**
     * Get groupsysts
     *
     * @return ArrayCollection
     */
    public function getGroupsysts()
    {
        return $this->groupsysts;
    }

    /**
     *
     * @param Collection $groupsysts
     *
     * @return Doc
     */
    public function setGroupsysts(Collection $groupsysts)
    {
        $this->groupsysts = $groupsysts;

        return $this;
    }

    /**
     * Add groupbank
     *
     * @param Docgroupbank $groupbank
     *
     * @return Doc
     */
    public function addGroupbank(Docgroupbank $groupbank)
    {
        $this->groupbanks[] = $groupbank;

        return $this;
    }

    /**
     * Remove groupbank
     *
     * @param Docgroupbank $groupbank
     *
     * @return Doc
     */
    public function removeGroupbank(Docgroupbank $groupbank)
    {
        $this->groupbanks->removeElement($groupbank);

        return $this;
    }

    /**
     * Get groupbanks
     *
     * @return ArrayCollection
     */
    public function getGroupbanks()
    {
        return $this->groupbanks;
    }

    /**
     *
     * @param Collection $groupbanks
     *
     * @return Doc
     */
    public function setGroupbanks(Collection $groupbanks)
    {
        $this->groupbanks = $groupbanks;

        return $this;
    }

    /**
     * Add groupaudit
     *
     * @param Docgroupaudit $groupaudit
     *
     * @return Doc
     */
    public function addGroupaudit(Docgroupaudit $groupaudit)
    {
        $this->groupaudits[] = $groupaudit;

        return $this;
    }

    /**
     * Remove groupaudit
     *
     * @param Docgroupaudit $groupaudit
     *
     * @return Doc
     */
    public function removeGroupaudit(Docgroupaudit $groupaudit)
    {
        $this->groupaudits->removeElement($groupaudit);

        return $this;
    }

    /**
     * Get groupaudits
     *
     * @return ArrayCollection
     */
    public function getGroupaudits()
    {
        return $this->groupaudits;
    }

    /**
     *
     * @param Collection $groupaudits
     *
     * @return Doc
     */
    public function setGroupaudits(Collection $groupaudits)
    {
        $this->groupaudits = $groupaudits;

        return $this;
    }

    /**
     * Add account
     *
     * @param Account $account
     *
     * @return Doc
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param Account $account
     *
     * @return Doc
     */
    public function removeAccount(Account $account)
    {
        $this->accounts->removeElement($account);

        return $this;
    }

    /**
     * Get accounts
     *
     * @return ArrayCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Get banks
     *
     * @return ArrayCollection
     */
    public function getBanks()
    {
        $banks = new ArrayCollection();
        foreach ($this->accounts as $account) {
            if ($account instanceof Bank) {
                $banks->add($account);
            }
        }

        return $banks;
    }

    /**
     * Get funds
     *
     * @return ArrayCollection
     */
    public function getFunds()
    {
        $funds = new ArrayCollection();
        foreach ($this->accounts as $account) {
            if ($account instanceof Fund) {
                $funds->add($account);
            }
        }

        return $funds;
    }

    /**
     *
     * @param Collection $accounts
     *
     * @return Doc
     */
    public function setAccounts(Collection $accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Add relation
     *
     * @param Relation $relation
     *
     * @return Doc
     */
    public function addRelation(Relation $relation)
    {
        $this->relations[] = $relation;

        return $this;
    }

    /**
     * Remove relation
     *
     * @param Relation $relation
     *
     * @return Doc
     */
    public function removeRelation(Relation $relation)
    {
        $this->relations->removeElement($relation);

        return $this;
    }

    /**
     * Get relations
     *
     * @return ArrayCollection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Get customers
     *
     * @return ArrayCollection
     */
    public function getCustomers()
    {
        $customers = new ArrayCollection();
        foreach ($this->relations as $relation) {
            if ($relation instanceof Customer) {
                $customers->add($relation);
            }
        }

        return $customers;
    }

    /**
     * Get suppliers
     *
     * @return ArrayCollection
     */
    public function getSuppliers()
    {
        $suppliers = new ArrayCollection();
        foreach ($this->relations as $relation) {
            if ($relation instanceof Supplier) {
                $suppliers->add($relation);
            }
        }

        return $suppliers;
    }

    /**
     *
     * @param Collection $relations
     *
     * @return Doc
     */
    public function setRelations(Collection $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Add monthlyBalance
     *
     * @param MonthlyBalance $monthlyBalance
     *
     * @return Doc
     */
    public function addMonthlyBalance(MonthlyBalance $monthlyBalance)
    {
        $this->monthlyBalances[] = $monthlyBalance;

        return $this;
    }

    /**
     * Remove monthlyBalance
     *
     * @param MonthlyBalance $monthlyBalance
     *
     * @return Doc
     */
    public function removeMonthlyBalance(MonthlyBalance $monthlyBalance)
    {
        $this->monthlyBalances->removeElement($monthlyBalance);

        return $this;
    }

    /**
     * Get monthlyBalances
     *
     * @return ArrayCollection
     */
    public function getMonthlyBalances()
    {
        return $this->monthlyBalances;
    }

    /**
     * Get Mbpurchases
     *
     * @return ArrayCollection
     */
    public function getMbpurchases()
    {
        $mbpurchases = new ArrayCollection();
        foreach ($this->monthlyBalances as $monthlyBalance) {
            if ($monthlyBalance instanceof MBPurchase) {
                $mbpurchases->add($monthlyBalance);
            }
        }

        return $mbpurchases;
    }

    /**
     * Get Mmbales
     *
     * @return ArrayCollection
     */
    public function getMbsales()
    {
        $mbsales = new ArrayCollection();
        foreach ($this->monthlyBalances as $monthlyBalance) {
            if ($monthlyBalance instanceof MBSale) {
                $mbsales->add($monthlyBalance);
            }
        }

        return $mbsales;
    }

    /**
     *
     * @param Collection $monthlyBalances
     *
     * @return Doc
     */
    public function setMonthlyBalances(Collection $monthlyBalances)
    {
        $this->monthlyBalances = $monthlyBalances;

        return $this;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return Doc
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
     * @return Doc
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
     * Get Sales
     *
     * @return ArrayCollection
     */
    public function getSales()
    {
        $sales = new ArrayCollection();
        foreach ($this->transactions as $transaction) {
            if ($transaction instanceof Sale) {
                $sales->add($transaction);
            }
        }

        return $sales;
    }

    /**
     * Get Buys
     *
     * @return ArrayCollection
     */
    public function getBuys()
    {
        $buys = new ArrayCollection();
        foreach ($this->transactions as $transaction) {
            if ($transaction instanceof Buy) {
                $buys->add($transaction);
            }
        }

        return $buys;
    }

    /**
     *
     * @param Collection $transactions
     *
     * @return Doc
     */
    public function setTransactions(Collection $transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' ' . $this->getFileName();
    }

    /**
     *
     */
    public function __clone()
    {

    }
}
