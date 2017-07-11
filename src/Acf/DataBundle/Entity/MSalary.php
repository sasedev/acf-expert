<?php
namespace Acf\DataBundle\Entity;

/**
 * MSalary
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MSalary
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var MonthlyBalance
     */
    protected $paye;

    /**
     *
     * @var string
     */
    protected $matricule;

    /**
     *
     * @var string
     */
    protected $nom;

    /**
     *
     * @var string
     */
    protected $prenom;

    /**
     *
     * @var string
     */
    protected $actif;

    /**
     *
     * @var string
     */
    protected $fonction;

    /**
     *
     * @var string
     */
    protected $regime;

    /**
     *
     * @var string
     */
    protected $dtStartContrat;

    /**
     *
     * @var string
     */
    protected $dtEndContrat;

    /**
     *
     * @var string
     */
    protected $departement;

    /**
     *
     * @var string
     */
    protected $categorie;

    /**
     *
     * @var string
     */
    protected $echelon;

    /**
     *
     * @var string
     */
    protected $cin;

    /**
     *
     * @var string
     */
    protected $cnss;

    /**
     *
     * @var string
     */
    protected $birthday;

    /**
     *
     * @var string
     */
    protected $adresse;

    /**
     *
     * @var string
     */
    protected $tel;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $banque;

    /**
     *
     * @var string
     */
    protected $rib;

    /**
     *
     * @var string
     */
    protected $familyChef;

    /**
     *
     * @var string
     */
    protected $familySituation;

    /**
     *
     * @var string
     */
    protected $handicap;

    /**
     *
     * @var string
     */
    protected $childWoBourse;

    /**
     *
     * @var string
     */
    protected $nbrDaysWork;

    /**
     *
     * @var string
     */
    protected $nbrDaysAbs;

    /**
     *
     * @var string
     */
    protected $nbrDaysFerry;

    /**
     *
     * @var string
     */
    protected $nbrH075Sup;

    /**
     *
     * @var string
     */
    protected $nbrH100Sup;

    /**
     *
     * @var string
     */
    protected $nbrDSup;

    /**
     *
     * @var string
     */
    protected $remboursement;

    /**
     *
     * @var string
     */
    protected $buysFromCompany;

    /**
     *
     * @var string
     */
    protected $salaryAdvance;

    /**
     *
     * @var string
     */
    protected $salaryBrut;

    /**
     *
     * @var string
     */
    protected $salaryNet;

    /**
     *
     * @var string
     */
    protected $advantageNature;

    /**
     *
     * @var string
     */
    protected $ticketResto;

    /**
     *
     * @var string
     */
    protected $ticketCadeau;

    /**
     *
     * @var string
     */
    protected $lifeAssurance;

    /**
     *
     * @var string
     */
    protected $ceaAccount;

    /**
     *
     * @var string
     */
    protected $others;

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
     * Get paye
     *
     * @return MPaye
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * Set paye
     *
     * @param MPaye $paye
     *
     * @return MSalary
     */
    public function setPaye(MPaye $paye = null)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->getPaye()->getCompany();
    }

    /**
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     *
     * @param string $matricule
     *
     * @return MSalary
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     *
     * @param string $nom
     *
     * @return MSalary
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     *
     * @param string $prenom
     *
     * @return MSalary
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     *
     * @param string $actif
     *
     * @return MSalary
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     *
     * @param string $fonction
     *
     * @return MSalary
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     *
     * @param string $regime
     *
     * @return MSalary
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDtStartContrat()
    {
        return $this->dtStartContrat;
    }

    /**
     *
     * @param string $dtStartContrat
     *
     * @return MSalary
     */
    public function setDtStartContrat($dtStartContrat)
    {
        $this->dtStartContrat = $dtStartContrat;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDtEndContrat()
    {
        return $this->dtEndContrat;
    }

    /**
     *
     * @param string $dtEndContrat
     *
     * @return MSalary
     */
    public function setDtEndContrat($dtEndContrat)
    {
        $this->dtEndContrat = $dtEndContrat;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     *
     * @param string $departement
     *
     * @return MSalary
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     *
     * @param string $categorie
     *
     * @return MSalary
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEchelon()
    {
        return $this->echelon;
    }

    /**
     *
     * @param string $echelon
     *
     * @return MSalary
     */
    public function setEchelon($echelon)
    {
        $this->echelon = $echelon;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     *
     * @param string $cin
     *
     * @return MSalary
     */
    public function setCin($cin)
    {
        $this->cin = $cin;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCnss()
    {
        return $this->cnss;
    }

    /**
     *
     * @param string $cnss
     *
     * @return MSalary
     */
    public function setCnss($cnss)
    {
        $this->cnss = $cnss;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     *
     * @param string $birthday
     *
     * @return MSalary
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     *
     * @param string $adresse
     *
     * @return MSalary
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
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
     * @return MSalary
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param string $email
     *
     * @return MSalary
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     *
     * @param string $banque
     *
     * @return MSalary
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     *
     * @param string $rib
     *
     * @return MSalary
     */
    public function setRib($rib)
    {
        $this->rib = $rib;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFamilyChef()
    {
        return $this->familyChef;
    }

    /**
     *
     * @param string $familyChef
     *
     * @return MSalary
     */
    public function setFamilyChef($familyChef)
    {
        $this->familyChef = $familyChef;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFamilySituation()
    {
        return $this->familySituation;
    }

    /**
     *
     * @param string $familySituation
     *
     * @return MSalary
     */
    public function setFamilySituation($familySituation)
    {
        $this->familySituation = $familySituation;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     *
     * @param string $handicap
     *
     * @return MSalary
     */
    public function setHandicap($handicap)
    {
        $this->handicap = $handicap;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getChildWoBourse()
    {
        return $this->childWoBourse;
    }

    /**
     *
     * @param string $childWoBourse
     *
     * @return MSalary
     */
    public function setChildWoBourse($childWoBourse)
    {
        $this->childWoBourse = $childWoBourse;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrDaysWork()
    {
        return $this->nbrDaysWork;
    }

    /**
     *
     * @param string $nbrDaysWork
     *
     * @return MSalary
     */
    public function setNbrDaysWork($nbrDaysWork)
    {
        $this->nbrDaysWork = $nbrDaysWork;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrDaysAbs()
    {
        return $this->nbrDaysAbs;
    }

    /**
     *
     * @param string $nbrDaysAbs
     *
     * @return MSalary
     */
    public function setNbrDaysAbs($nbrDaysAbs)
    {
        $this->nbrDaysAbs = $nbrDaysAbs;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrDaysFerry()
    {
        return $this->nbrDaysFerry;
    }

    /**
     *
     * @param string $nbrDaysFerry
     *
     * @return MSalary
     */
    public function setNbrDaysFerry($nbrDaysFerry)
    {
        $this->nbrDaysFerry = $nbrDaysFerry;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrH075Sup()
    {
        return $this->nbrH075Sup;
    }

    /**
     *
     * @param string $nbrH075Sup
     *
     * @return MSalary
     */
    public function setNbrH075Sup($nbrH075Sup)
    {
        $this->nbrH075Sup = $nbrH075Sup;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrH100Sup()
    {
        return $this->nbrH100Sup;
    }

    /**
     *
     * @param string $nbrH100Sup
     *
     * @return MSalary
     */
    public function setNbrH100Sup($nbrH100Sup)
    {
        $this->nbrH100Sup = $nbrH100Sup;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNbrDSup()
    {
        return $this->nbrDSup;
    }

    /**
     *
     * @param string $nbrDSup
     *
     * @return MSalary
     */
    public function setNbrDSup($nbrDSup)
    {
        $this->nbrDSup = $nbrDSup;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRemboursement()
    {
        return $this->remboursement;
    }

    /**
     *
     * @param string $remboursement
     *
     * @return MSalary
     */
    public function setRemboursement($remboursement)
    {
        $this->remboursement = $remboursement;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getBuysFromCompany()
    {
        return $this->buysFromCompany;
    }

    /**
     *
     * @param string $buysFromCompany
     *
     * @return MSalary
     */
    public function setBuysFromCompany($buysFromCompany)
    {
        $this->buysFromCompany = $buysFromCompany;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSalaryAdvance()
    {
        return $this->salaryAdvance;
    }

    /**
     *
     * @param string $salaryAdvance
     *
     * @return MSalary
     */
    public function setSalaryAdvance($salaryAdvance)
    {
        $this->salaryAdvance = $salaryAdvance;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSalaryBrut()
    {
        return $this->salaryBrut;
    }

    /**
     *
     * @param string $salaryBrut
     *
     * @return MSalary
     */
    public function setSalaryBrut($salaryBrut)
    {
        $this->salaryBrut = $salaryBrut;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSalaryNet()
    {
        return $this->salaryNet;
    }

    /**
     *
     * @param string $salaryNet
     *
     * @return MSalary
     */
    public function setSalaryNet($salaryNet)
    {
        $this->salaryNet = $salaryNet;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAdvantageNature()
    {
        return $this->advantageNature;
    }

    /**
     *
     * @param string $advantageNature
     *
     * @return MSalary
     */
    public function setAdvantageNature($advantageNature)
    {
        $this->advantageNature = $advantageNature;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTicketResto()
    {
        return $this->ticketResto;
    }

    /**
     *
     * @param string $ticketResto
     *
     * @return MSalary
     */
    public function setTicketResto($ticketResto)
    {
        $this->ticketResto = $ticketResto;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTicketCadeau()
    {
        return $this->ticketCadeau;
    }

    /**
     *
     * @param string $ticketCadeau
     *
     * @return MSalary
     */
    public function setTicketCadeau($ticketCadeau)
    {
        $this->ticketCadeau = $ticketCadeau;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLifeAssurance()
    {
        return $this->lifeAssurance;
    }

    /**
     *
     * @param string $lifeAssurance
     *
     * @return MSalary
     */
    public function setLifeAssurance($lifeAssurance)
    {
        $this->lifeAssurance = $lifeAssurance;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCeaAccount()
    {
        return $this->ceaAccount;
    }

    /**
     *
     * @param string $ceaAccount
     *
     * @return MSalary
     */
    public function setCeaAccount($ceaAccount)
    {
        $this->ceaAccount = $ceaAccount;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getOthers()
    {
        return $this->others;
    }

    /**
     *
     * @param string $others
     *
     * @return MSalary
     */
    public function setOthers($others)
    {
        $this->others = $others;
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
     * @return MSalary
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
     * @return MSalary
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