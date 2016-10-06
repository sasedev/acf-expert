<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BulletinInfoContent
 * @ORM\Table(name="acf_bi_contents")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BulletinInfoContentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BulletinInfoContent
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
     * @var BulletinInfoTitle @ORM\ManyToOne(targetEntity="BulletinInfoTitle", inversedBy="contents", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="bt_id", referencedColumnName="id")
     *      })
     */
    protected $bulletinInfoTitle;

    /**
     *
     * @var string @ORM\Column(name="bc_title", type="text", nullable=false)
     *      @Assert\NotBlank(groups={"title"})
     */
    protected $title;

    /**
     *
     * @var string @ORM\Column(name="bc_content", type="text", nullable=true)
     */
    protected $content;

    /**
     *
     * @var string @ORM\Column(name="bc_theme", type="text", nullable=true)
     */
    protected $theme;

    /**
     *
     * @var string @ORM\Column(name="bc_jort", type="text", nullable=true)
     */
    protected $jort;

    /**
     *
     * @var string @ORM\Column(name="bc_txtnum", type="text", nullable=true)
     */
    protected $txtNum;

    /**
     *
     * @var string @ORM\Column(name="bc_arttxt", type="text", nullable=true)
     */
    protected $artTxt;

    /**
     *
     * @var string @ORM\Column(name="bc_dttxt", type="text", nullable=true)
     */
    protected $dtTxt;

    /**
     *
     * @var string @ORM\Column(name="bc_artcode", type="text", nullable=true)
     */
    protected $artCode;

    /**
     *
     * @var string @ORM\Column(name="bc_stetype", type="text", nullable=true)
     */
    protected $companyType;

    /**
     *
     * @var string @ORM\Column(name="bc_dtapp", type="text", nullable=true)
     */
    protected $dtApplication;

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
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');
    }

    /**
     * Get $id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $bulletinInfoTitle
     *
     * @return BulletinInfoTitle
     */
    public function getBulletinInfoTitle()
    {
        return $this->bulletinInfoTitle;
    }

    /**
     * Set $bulletinInfoTitle
     *
     * @param BulletinInfoTitle $bulletinInfoTitle
     *
     * @return BulletinInfoContent
     */
    public function setBulletinInfoTitle(BulletinInfoTitle $bulletinInfoTitle)
    {
        $this->bulletinInfoTitle = $bulletinInfoTitle;

        return $this;
    }

    /**
     * Get $title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set $title
     *
     * @param string $title
     *
     * @return BulletinInfoContent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get $content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set $content
     *
     * @param string $content
     *
     * @return BulletinInfoContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get $theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set $theme
     *
     * @param string $theme
     *
     * @return BulletinInfoContent
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get $jort
     *
     * @return string
     */
    public function getJort()
    {
        return $this->jort;
    }

    /**
     * Set $jort
     *
     * @param string $jort
     *
     * @return BulletinInfoContent
     */
    public function setJort($jort)
    {
        $this->jort = $jort;

        return $this;
    }

    /**
     * Get $txtNum
     *
     * @return string
     */
    public function getTxtNum()
    {
        return $this->txtNum;
    }

    /**
     * Set $txtNum
     *
     * @param string $txtNum
     *
     * @return BulletinInfoContent
     */
    public function setTxtNum($txtNum)
    {
        $this->txtNum = $txtNum;

        return $this;
    }

    /**
     * Get $artTxt
     *
     * @return string
     */
    public function getArtTxt()
    {
        return $this->artTxt;
    }

    /**
     * Set $artTxt
     *
     * @param string $artTxt
     *
     * @return BulletinInfoContent
     */
    public function setArtTxt($artTxt)
    {
        $this->artTxt = $artTxt;

        return $this;
    }

    /**
     * Get $dtTxt
     *
     * @return string
     */
    public function getDtTxt()
    {
        return $this->dtTxt;
    }

    /**
     * Set $dtTxt
     *
     * @param string $dtTxt
     *
     * @return BulletinInfoContent
     */
    public function setDtTxt($dtTxt)
    {
        $this->dtTxt = $dtTxt;

        return $this;
    }

    /**
     * Get $artCode
     *
     * @return string
     */
    public function getArtCode()
    {
        return $this->artCode;
    }

    /**
     * Set $artCode
     *
     * @param string $artCode
     *
     * @return BulletinInfoContent
     */
    public function setArtCode($artCode)
    {
        $this->artCode = $artCode;

        return $this;
    }

    /**
     * Get $companyType
     *
     * @return string
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * Set $companyType
     *
     * @param string $companyType
     *
     * @return BulletinInfoContent
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;

        return $this;
    }

    /**
     * Get $dtApplication
     *
     * @return string
     */
    public function getDtApplication()
    {
        return $this->dtApplication;
    }

    /**
     * Set $dtApplication
     *
     * @param string $dtApplication
     *
     * @return BulletinInfoContent
     */
    public function setDtApplication($dtApplication)
    {
        $this->dtApplication = $dtApplication;

        return $this;
    }

    /**
     * Get $dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set $dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return BulletinInfoContent
     */
    public function setDtCrea(\DateTime $dtCrea)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get $dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set $dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return BulletinInfoContent
     */
    public function setDtUpdate(\DateTime $dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     */
    public function __clone()
    {}
}
