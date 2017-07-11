<?php
namespace Acf\DataBundle\Entity;

/**
 * Pilot
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Pilot
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
    protected $ref;

    /**
     *
     * @var string
     */
    protected $mission;

    /**
     *
     * @var string
     */
    protected $natureMission;

    /**
     *
     * @var string
     */
    protected $prestataire;

    /**
     *
     * @var string
     */
    protected $recetteFinance;

    /**
     *
     * @var string
     */
    protected $pinAnce;

    /**
     *
     * @var string
     */
    protected $expirationAnce;

    /**
     *
     * @var string
     */
    protected $mpImpots;

    /**
     *
     * @var string
     */
    protected $idCnss;

    /**
     *
     * @var string
     */
    protected $mpCnss;

    /**
     *
     * @var string
     */
    protected $nomCac;

    /**
     *
     * @var string
     */
    protected $dureeMandat;

    /**
     *
     * @var string
     */
    protected $numMandat;

    /**
     *
     * @var string
     */
    protected $rapportCac;

    /**
     *
     * @var string
     */
    protected $declEmpl;

    /**
     *
     * @var string
     */
    protected $isDur;

    /**
     *
     * @var string
     */
    protected $pvCa;

    /**
     *
     * @var string
     */
    protected $rapportGerance;

    /**
     *
     * @var string
     */
    protected $pvAgo;

    /**
     *
     * @var string
     */
    protected $pvAge;

    /**
     *
     * @var string
     */
    protected $livresCotes;

    /**
     *
     * @var float
     */
    protected $honTeorAnn;

    /**
     *
     * @var string
     */
    protected $modeFact;

    /**
     *
     * @var float
     */
    protected $nonFactMont;

    /**
     *
     * @var string
     */
    protected $nonFactDesc;

    /**
     *
     * @var float
     */
    protected $nonEncMont;

    /**
     *
     * @var string
     */
    protected $nonEncDesc;

    /**
     *
     * @var string
     */
    protected $commentQuit;

    /**
     *
     * @var string
     */
    protected $mqQuitImpots;

    /**
     *
     * @var string
     */
    protected $mqQuitCnss;

    /**
     *
     * @var string
     */
    protected $comments;

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
     * @return Pilot
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     *
     * @param string $ref
     *
     * @return Pilot
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     *
     * @param string $mission
     *
     * @return Pilot
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNatureMission()
    {
        return $this->natureMission;
    }

    /**
     *
     * @param string $natureMission
     *
     * @return Pilot
     */
    public function setNatureMission($natureMission)
    {
        $this->natureMission = $natureMission;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPrestataire()
    {
        return $this->prestataire;
    }

    /**
     *
     * @param string $prestataire
     *
     * @return Pilot
     */
    public function setPrestataire($prestataire)
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRecetteFinance()
    {
        return $this->recetteFinance;
    }

    /**
     *
     * @param string $recetteFinance
     *
     * @return Pilot
     */
    public function setRecetteFinance($recetteFinance)
    {
        $this->recetteFinance = $recetteFinance;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPinAnce()
    {
        return $this->pinAnce;
    }

    /**
     *
     * @param string $pinAnce
     *
     * @return Pilot
     */
    public function setPinAnce($pinAnce)
    {
        $this->pinAnce = $pinAnce;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getExpirationAnce()
    {
        return $this->expirationAnce;
    }

    /**
     *
     * @param string $expirationAnce
     *
     * @return Pilot
     */
    public function setExpirationAnce($expirationAnce)
    {
        $this->expirationAnce = $expirationAnce;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMpImpots()
    {
        return $this->mpImpots;
    }

    /**
     *
     * @param string $mpImpots
     *
     * @return Pilot
     */
    public function setMpImpots($mpImpots)
    {
        $this->mpImpots = $mpImpots;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getIdCnss()
    {
        return $this->idCnss;
    }

    /**
     *
     * @param string $idCnss
     *
     * @return Pilot
     */
    public function setIdCnss($idCnss)
    {
        $this->idCnss = $idCnss;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMpCnss()
    {
        return $this->mpCnss;
    }

    /**
     *
     * @param string $mpCnss
     *
     * @return Pilot
     */
    public function setMpCnss($mpCnss)
    {
        $this->mpCnss = $mpCnss;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNomCac()
    {
        return $this->nomCac;
    }

    /**
     *
     * @param string $nomCac
     *
     * @return Pilot
     */
    public function setNomCac($nomCac)
    {
        $this->nomCac = $nomCac;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDureeMandat()
    {
        return $this->dureeMandat;
    }

    /**
     *
     * @param string $dureeMandat
     *
     * @return Pilot
     */
    public function setDureeMandat($dureeMandat)
    {
        $this->dureeMandat = $dureeMandat;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNumMandat()
    {
        return $this->numMandat;
    }

    /**
     *
     * @param string $numMandat
     *
     * @return Pilot
     */
    public function setNumMandat($numMandat)
    {
        $this->numMandat = $numMandat;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRapportCac()
    {
        return $this->rapportCac;
    }

    /**
     *
     * @param string $rapportCac
     *
     * @return Pilot
     */
    public function setRapportCac($rapportCac)
    {
        $this->rapportCac = $rapportCac;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDeclEmpl()
    {
        return $this->declEmpl;
    }

    /**
     *
     * @param string $declEmpl
     *
     * @return Pilot
     */
    public function setDeclEmpl($declEmpl)
    {
        $this->declEmpl = $declEmpl;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getIsDur()
    {
        return $this->isDur;
    }

    /**
     *
     * @param string $isDur
     *
     * @return Pilot
     */
    public function setIsDur($isDur)
    {
        $this->isDur = $isDur;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPvCa()
    {
        return $this->pvCa;
    }

    /**
     *
     * @param string $pvCa
     *
     * @return Pilot
     */
    public function setPvCa($pvCa)
    {
        $this->pvCa = $pvCa;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRapportGerance()
    {
        return $this->rapportGerance;
    }

    /**
     *
     * @param string $rapportGerance
     *
     * @return Pilot
     */
    public function setRapportGerance($rapportGerance)
    {
        $this->rapportGerance = $rapportGerance;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPvAge()
    {
        return $this->pvAge;
    }

    /**
     *
     * @param string $pvAge
     *
     * @return Pilot
     */
    public function setPvAge($pvAge)
    {
        $this->pvAge = $pvAge;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPvAgo()
    {
        return $this->pvAgo;
    }

    /**
     *
     * @param string $pvAgo
     *
     * @return Pilot
     */
    public function setPvAgo($pvAgo)
    {
        $this->pvAgo = $pvAgo;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLivresCotes()
    {
        return $this->livresCotes;
    }

    /**
     *
     * @param string $livresCotes
     *
     * @return Pilot
     */
    public function setLivresCotes($livresCotes)
    {
        $this->livresCotes = $livresCotes;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getHonTeorAnn()
    {
        return $this->honTeorAnn;
    }

    /**
     *
     * @param float $honTeorAnn
     *
     * @return Pilot
     */
    public function setHonTeorAnn($honTeorAnn)
    {
        $this->honTeorAnn = $honTeorAnn;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getModeFact()
    {
        return $this->modeFact;
    }

    /**
     *
     * @param string $modeFact
     *
     * @return Pilot
     */
    public function setModeFact($modeFact)
    {
        $this->modeFact = $modeFact;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getNonFactMont()
    {
        return $this->nonFactMont;
    }

    /**
     *
     * @param float $nonFactMont
     *
     * @return Pilot
     */
    public function setNonFactMont($nonFactMont)
    {
        $this->nonFactMont = $nonFactMont;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNonFactDesc()
    {
        return $this->nonFactDesc;
    }

    /**
     *
     * @param string $nonFactDesc
     *
     * @return Pilot
     */
    public function setNonFactDesc($nonFactDesc)
    {
        $this->nonFactDesc = $nonFactDesc;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getNonEncMont()
    {
        return $this->nonEncMont;
    }

    /**
     *
     * @param float $nonEncMont
     *
     * @return Pilot
     */
    public function setNonEncMont($nonEncMont)
    {
        $this->nonEncMont = $nonEncMont;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getNonEncDesc()
    {
        return $this->nonEncDesc;
    }

    /**
     *
     * @param string $nonEncDesc
     *
     * @return Pilot
     */
    public function setNonEncDesc($nonEncDesc)
    {
        $this->nonEncDesc = $nonEncDesc;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCommentQuit()
    {
        return $this->commentQuit;
    }

    /**
     *
     * @param string $commentQuit
     *
     * @return Pilot
     */
    public function setCommentQuit($commentQuit)
    {
        $this->commentQuit = $commentQuit;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMqQuitImpots()
    {
        return $this->mqQuitImpots;
    }

    /**
     *
     * @param string $mqQuitImpots
     *
     * @return Pilot
     */
    public function setMqQuitImpots($mqQuitImpots)
    {
        $this->mqQuitImpots = $mqQuitImpots;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMqQuitCnss()
    {
        return $this->mqQuitCnss;
    }

    /**
     *
     * @param string $mqQuitCnss
     *
     * @return Pilot
     */
    public function setMqQuitCnss($mqQuitCnss)
    {
        $this->mqQuitCnss = $mqQuitCnss;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     *
     * @param string $comments
     *
     * @return Pilot
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

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
     * @return Pilot
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
     * @return Pilot
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
