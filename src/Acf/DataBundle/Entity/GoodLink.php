<?php
namespace Acf\DataBundle\Entity;

/**
 * GoodLink
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class GoodLink
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $url;

    /**
     *
     * @var integer
     */
    protected $nbrClicks;

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
        $this->size = 0;
        $this->nbrClicks = 0;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Doc
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @param string $url
     *
     * @return GoodLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get nbrClicks
     *
     * @return integer
     */
    public function getNbrClicks()
    {
        return $this->nbrClicks;
    }

    /**
     * Set nbrClicks
     *
     * @param integer $nbrClicks
     *
     * @return Doc
     */
    public function setNbrClicks($nbrClicks)
    {
        $this->nbrClicks = $nbrClicks;

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
     */
    public function __clone()
    {
    }
}
