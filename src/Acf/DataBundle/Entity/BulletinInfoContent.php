<?php
namespace Acf\DataBundle\Entity;

/**
 * BulletinInfoContent
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BulletinInfoContent
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var BulletinInfoTitle
     */
    protected $bulletinInfoTitle;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $content;

    /**
     *
     * @var string
     */
    protected $theme;

    /**
     *
     * @var string
     */
    protected $jort;

    /**
     *
     * @var string
     */
    protected $txtNum;

    /**
     *
     * @var string
     */
    protected $artTxt;

    /**
     *
     * @var string
     */
    protected $dtTxt;

    /**
     *
     * @var string
     */
    protected $artCode;

    /**
     *
     * @var string
     */
    protected $companyType;

    /**
     *
     * @var string
     */
    protected $dtApplication;

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
     * Get $id
     *
     * @return string
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
    {
    }
}
