<?php
namespace Acf\DataBundle\Entity;

/**
 * FeedRead
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class FeedRead
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
    protected $url;

    /**
     *
     * @var integer
     */
    protected $nbrDays;

    /**
     *
     * @var integer
     */
    protected $nbrItems;

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
        $this->nbrDays = 7;
        $this->nbrItems = 3;
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
     * @return FeedRead
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getNbrDays()
    {
        return $this->nbrDays;
    }

    /**
     *
     * @param integer $nbrDays
     *
     * @return FeedRead
     */
    public function setNbrDays($nbrDays)
    {
        $this->nbrDays = $nbrDays;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getNbrItems()
    {
        return $this->nbrItems;
    }

    /**
     *
     * @param integer $nbrItems
     *
     * @return FeedRead
     */
    public function setNbrItems($nbrItems)
    {
        $this->nbrItems = $nbrItems;

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
     * @return FeedRead
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
     * @return FeedRead
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }
}
