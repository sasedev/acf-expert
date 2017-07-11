<?php
namespace Acf\DataBundle\Entity;

/**
 * CompanyUser
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyUser
{

    /**
     *
     * @var integer
     */
    const CANT = 1;

    /**
     *
     * @var integer
     */
    const CAN = 2;

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
     * @var User
     */
    protected $user;

    /**
     *
     * @var integer
     */
    protected $editCompanyinfos;

    /**
     *
     * @var integer
     */
    protected $addAddresses;

    /**
     *
     * @var integer
     */
    protected $editAddresses;

    /**
     *
     * @var integer
     */
    protected $deleteAddresses;

    /**
     *
     * @var integer
     */
    protected $addPhones;

    /**
     *
     * @var integer
     */
    protected $editPhones;

    /**
     *
     * @var integer
     */
    protected $deletePhones;

    /**
     *
     * @var integer
     */
    protected $addFrames;

    /**
     *
     * @var integer
     */
    protected $editFrames;

    /**
     *
     * @var integer
     */
    protected $deleteFrames;

    /**
     *
     * @var integer
     */
    protected $addDocs;

    /**
     *
     * @var integer
     */
    protected $editDocs;

    /**
     *
     * @var integer
     */
    protected $deleteDocs;

    /**
     *
     * @var integer
     */
    protected $addSuppliers;

    /**
     *
     * @var integer
     */
    protected $editSuppliers;

    /**
     *
     * @var integer
     */
    protected $deleteSuppliers;

    /**
     *
     * @var integer @
     */
    protected $addCustomers;

    /**
     *
     * @var integer
     */
    protected $editCustomers;

    /**
     *
     * @var integer
     */
    protected $deleteCustomers;

    /**
     *
     * @var integer
     */
    protected $addSales;

    /**
     *
     * @var integer
     */
    protected $editSales;

    /**
     *
     * @var integer
     */
    protected $deleteSales;

    /**
     *
     * @var integer
     */
    protected $addBuys;

    /**
     *
     * @var integer
     */
    protected $editBuys;

    /**
     *
     * @var integer
     */
    protected $deleteBuys;

    /**
     *
     * @var integer
     */
    protected $addDocgroupComptables;

    /**
     *
     * @var integer
     */
    protected $editDocgroupComptables;

    /**
     *
     * @var integer
     */
    protected $addDocgroupBanks;

    /**
     *
     * @var integer
     */
    protected $editDocgroupBanks;

    /**
     *
     * @var integer
     */
    protected $addDocgroupJuridics;

    /**
     *
     * @var integer
     */
    protected $editDocgroupJuridics;

    /**
     *
     * @var integer
     */
    protected $addDocgroupFiscals;

    /**
     *
     * @var integer
     */
    protected $editDocgroupFiscals;

    /**
     *
     * @var integer @
     */
    protected $addDocgroupPersos;

    /**
     *
     * @var integer
     */
    protected $editDocgroupPersos;

    /**
     *
     * @var integer
     */
    protected $addDocgroupSysts;

    /**
     *
     * @var integer
     */
    protected $editDocgroupSysts;

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

        $this->editCompanyinfos = self::CANT;
        $this->addAddresses = self::CANT;
        $this->editAddresses = self::CANT;
        $this->deleteAddresses = self::CANT;
        $this->addPhones = self::CANT;
        $this->editPhones = self::CANT;
        $this->deletePhones = self::CANT;
        $this->addFrames = self::CANT;
        $this->editFrames = self::CANT;
        $this->deleteFrames = self::CANT;
        $this->addDocs = self::CANT;
        $this->editDocs = self::CANT;
        $this->deleteDocs = self::CANT;
        $this->addSuppliers = self::CANT;
        $this->editSuppliers = self::CANT;
        $this->deleteSuppliers = self::CANT;
        $this->addCustomers = self::CANT;
        $this->editCustomers = self::CANT;
        $this->deleteCustomers = self::CANT;
        $this->addSales = self::CANT;
        $this->editSales = self::CANT;
        $this->deleteSales = self::CANT;
        $this->addBuys = self::CANT;
        $this->editBuys = self::CANT;
        $this->deleteBuys = self::CANT;
        $this->addDocgroupComptables = self::CANT;
        $this->editDocgroupComptables = self::CANT;
        $this->addDocgroupBanks = self::CANT;
        $this->editDocgroupBanks = self::CANT;
        $this->addDocgroupJuridics = self::CANT;
        $this->editDocgroupJuridics = self::CANT;
        $this->addDocgroupFiscals = self::CANT;
        $this->editDocgroupFiscals = self::CANT;
        $this->addDocgroupPersos = self::CANT;
        $this->editDocgroupPersos = self::CANT;
        $this->addDocgroupSysts = self::CANT;
        $this->editDocgroupSysts = self::CANT;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param Company $company
     *
     * @return CompanyUser
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user
     *
     * @return CompanyUser
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditCompanyinfos()
    {
        return $this->editCompanyinfos;
    }

    /**
     *
     * @param integer $editCompanyinfos
     *
     * @return CompanyUser
     */
    public function setEditCompanyinfos($editCompanyinfos)
    {
        $this->editCompanyinfos = $editCompanyinfos;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddAddresses()
    {
        return $this->addAddresses;
    }

    /**
     *
     * @param integer $addAddresses
     *
     * @return CompanyUser
     */
    public function setAddAddresses($addAddresses)
    {
        $this->addAddresses = $addAddresses;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditAddresses()
    {
        return $this->editAddresses;
    }

    /**
     *
     * @param integer $editAddresses
     *
     * @return CompanyUser
     */
    public function setEditAddresses($editAddresses)
    {
        $this->editAddresses = $editAddresses;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteAddresses()
    {
        return $this->deleteAddresses;
    }

    /**
     *
     * @param integer $deleteAddresses
     *
     * @return CompanyUser
     */
    public function setDeleteAddresses($deleteAddresses)
    {
        $this->deleteAddresses = $deleteAddresses;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddPhones()
    {
        return $this->addPhones;
    }

    /**
     *
     * @param integer $addPhones
     *
     * @return CompanyUser
     */
    public function setAddPhones($addPhones)
    {
        $this->addPhones = $addPhones;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditPhones()
    {
        return $this->editPhones;
    }

    /**
     *
     * @param integer $editPhones
     *
     * @return CompanyUser
     */
    public function setEditPhones($editPhones)
    {
        $this->editPhones = $editPhones;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeletePhones()
    {
        return $this->deletePhones;
    }

    /**
     *
     * @param integer $deletePhones
     *
     * @return CompanyUser
     */
    public function setDeletePhones($deletePhones)
    {
        $this->deletePhones = $deletePhones;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddFrames()
    {
        return $this->addFrames;
    }

    /**
     *
     * @param integer $addFrames
     *
     * @return CompanyUser
     */
    public function setAddFrames($addFrames)
    {
        $this->addFrames = $addFrames;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditFrames()
    {
        return $this->editFrames;
    }

    /**
     *
     * @param integer $editFrames
     *
     * @return CompanyUser
     */
    public function setEditFrames($editFrames)
    {
        $this->editFrames = $editFrames;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteFrames()
    {
        return $this->deleteFrames;
    }

    /**
     *
     * @param integer $deleteFrames
     *
     * @return CompanyUser
     */
    public function setDeleteFrames($deleteFrames)
    {
        $this->deleteFrames = $deleteFrames;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocs()
    {
        return $this->addDocs;
    }

    /**
     *
     * @param integer $addDocs
     *
     * @return CompanyUser
     */
    public function setAddDocs($addDocs)
    {
        $this->addDocs = $addDocs;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocs()
    {
        return $this->editDocs;
    }

    /**
     *
     * @param integer $editDocs
     *
     * @return CompanyUser
     */
    public function setEditDocs($editDocs)
    {
        $this->editDocs = $editDocs;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteDocs()
    {
        return $this->deleteDocs;
    }

    /**
     *
     * @param integer $deleteDocs
     *
     * @return CompanyUser
     */
    public function setDeleteDocs($deleteDocs)
    {
        $this->deleteDocs = $deleteDocs;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddSuppliers()
    {
        return $this->addSuppliers;
    }

    /**
     *
     * @param integer $addSuppliers
     *
     * @return CompanyUser
     */
    public function setAddSuppliers($addSuppliers)
    {
        $this->addSuppliers = $addSuppliers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditSuppliers()
    {
        return $this->editSuppliers;
    }

    /**
     *
     * @param integer $editSuppliers
     *
     * @return CompanyUser
     */
    public function setEditSuppliers($editSuppliers)
    {
        $this->editSuppliers = $editSuppliers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteSuppliers()
    {
        return $this->deleteSuppliers;
    }

    /**
     *
     * @param integer $deleteSuppliers
     *
     * @return CompanyUser
     */
    public function setDeleteSuppliers($deleteSuppliers)
    {
        $this->deleteSuppliers = $deleteSuppliers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddCustomers()
    {
        return $this->addCustomers;
    }

    /**
     *
     * @param integer $addCustomers
     *
     * @return CompanyUser
     */
    public function setAddCustomers($addCustomers)
    {
        $this->addCustomers = $addCustomers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditCustomers()
    {
        return $this->editCustomers;
    }

    /**
     *
     * @param integer $editCustomers
     *
     * @return CompanyUser
     */
    public function setEditCustomers($editCustomers)
    {
        $this->editCustomers = $editCustomers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteCustomers()
    {
        return $this->deleteCustomers;
    }

    /**
     *
     * @param integer $deleteCustomers
     *
     * @return CompanyUser
     */
    public function setDeleteCustomers($deleteCustomers)
    {
        $this->deleteCustomers = $deleteCustomers;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddSales()
    {
        return $this->addSales;
    }

    /**
     *
     * @param integer $addSales
     *
     * @return CompanyUser
     */
    public function setAddSales($addSales)
    {
        $this->addSales = $addSales;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditSales()
    {
        return $this->editSales;
    }

    /**
     *
     * @param integer $editSales
     *
     * @return CompanyUser
     */
    public function setEditSales($editSales)
    {
        $this->editSales = $editSales;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteSales()
    {
        return $this->deleteSales;
    }

    /**
     *
     * @param integer $deleteSales
     *
     * @return CompanyUser
     */
    public function setDeleteSales($deleteSales)
    {
        $this->deleteSales = $deleteSales;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddBuys()
    {
        return $this->addBuys;
    }

    /**
     *
     * @param integer $addBuys
     *
     * @return CompanyUser
     */
    public function setAddBuys($addBuys)
    {
        $this->addBuys = $addBuys;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditBuys()
    {
        return $this->editBuys;
    }

    /**
     *
     * @param integer $editBuys
     *
     * @return CompanyUser
     */
    public function setEditBuys($editBuys)
    {
        $this->editBuys = $editBuys;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getDeleteBuys()
    {
        return $this->deleteBuys;
    }

    /**
     *
     * @param integer $deleteBuys
     *
     * @return CompanyUser
     */
    public function setDeleteBuys($deleteBuys)
    {
        $this->deleteBuys = $deleteBuys;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupComptables()
    {
        return $this->addDocgroupComptables;
    }

    /**
     *
     * @param integer $addDocgroupComptables
     *
     * @return CompanyUser
     */
    public function setAddDocgroupComptables($addDocgroupComptables)
    {
        $this->addDocgroupComptables = $addDocgroupComptables;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupComptables()
    {
        return $this->editDocgroupComptables;
    }

    /**
     *
     * @param integer $editDocgroupComptables
     *
     * @return CompanyUser
     */
    public function setEditDocgroupComptables($editDocgroupComptables)
    {
        $this->editDocgroupComptables = $editDocgroupComptables;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupBanks()
    {
        return $this->addDocgroupBanks;
    }

    /**
     *
     * @param integer $addDocgroupBanks
     *
     * @return CompanyUser
     */
    public function setAddDocgroupBanks($addDocgroupBanks)
    {
        $this->addDocgroupBanks = $addDocgroupBanks;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupBanks()
    {
        return $this->editDocgroupBanks;
    }

    /**
     *
     * @param integer $editDocgroupBanks
     *
     * @return CompanyUser
     */
    public function setEditDocgroupBanks($editDocgroupBanks)
    {
        $this->editDocgroupBanks = $editDocgroupBanks;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupJuridics()
    {
        return $this->addDocgroupJuridics;
    }

    /**
     *
     * @param integer $addDocgroupJuridics
     *
     * @return CompanyUser
     */
    public function setAddDocgroupJuridics($addDocgroupJuridics)
    {
        $this->addDocgroupJuridics = $addDocgroupJuridics;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupJuridics()
    {
        return $this->editDocgroupJuridics;
    }

    /**
     *
     * @param integer $editDocgroupJuridics
     *
     * @return CompanyUser
     */
    public function setEditDocgroupJuridics($editDocgroupJuridics)
    {
        $this->editDocgroupJuridics = $editDocgroupJuridics;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupFiscals()
    {
        return $this->addDocgroupFiscals;
    }

    /**
     *
     * @param integer $addDocgroupFiscals
     *
     * @return CompanyUser
     */
    public function setAddDocgroupFiscals($addDocgroupFiscals)
    {
        $this->addDocgroupFiscals = $addDocgroupFiscals;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupFiscals()
    {
        return $this->editDocgroupFiscals;
    }

    /**
     *
     * @param integer $editDocgroupFiscals
     *
     * @return CompanyUser
     */
    public function setEditDocgroupFiscals($editDocgroupFiscals)
    {
        $this->editDocgroupFiscals = $editDocgroupFiscals;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupPersos()
    {
        return $this->addDocgroupPersos;
    }

    /**
     *
     * @param integer $addDocgroupPersos
     *
     * @return CompanyUser
     */
    public function setAddDocgroupPersos($addDocgroupPersos)
    {
        $this->addDocgroupPersos = $addDocgroupPersos;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupPersos()
    {
        return $this->editDocgroupPersos;
    }

    /**
     *
     * @param integer $editDocgroupPersos
     *
     * @return CompanyUser
     */
    public function setEditDocgroupPersos($editDocgroupPersos)
    {
        $this->editDocgroupPersos = $editDocgroupPersos;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getAddDocgroupSysts()
    {
        return $this->addDocgroupSysts;
    }

    /**
     *
     * @param integer $addDocgroupSysts
     *
     * @return CompanyUser
     */
    public function setAddDocgroupSysts($addDocgroupSysts)
    {
        $this->addDocgroupSysts = $addDocgroupSysts;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getEditDocgroupSysts()
    {
        return $this->editDocgroupSysts;
    }

    /**
     *
     * @param integer $editDocgroupSysts
     *
     * @return CompanyUser
     */
    public function setEditDocgroupSysts($editDocgroupSysts)
    {
        $this->editDocgroupSysts = $editDocgroupSysts;

        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     *
     * @param \DateTime $dtCrea
     *
     * @return CompanyUser
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     *
     * @param \DateTime $dtUpdate
     *
     * @return CompanyUser
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Choice Form TrueFalse
     *
     * @return multitype:string
     */
    public static function choiceTF()
    {
        return array(
            'CompanyUser.tf.choice.' . self::CANT => self::CANT,
            'CompanyUser.tf.choice.' . self::CAN => self::CAN
        );
    }

    /**
     * Choice Validator TrueFalse
     *
     * @return multitype:integer
     */
    public static function choiceTFCallback()
    {
        return array(
            self::CANT,
            self::CAN
        );
    }

    /**
     */
    public function __clone()
    {
    }
}
