<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GoodLink
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_goodlinks")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\GoodLinkRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"url"}, errorPath="url", groups={"url"})
 */
class GoodLink
{

    /**
     *
     * @var string @ORM\Column(name="id", type="guid", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="title", type="text", nullable=false)
     */
    protected $title;

    /**
     *
     * @var string @ORM\Column(name="url", type="text", nullable=false)
     *      @Assert\Url(checkDNS=true, groups={"url"})
     */
    protected $url;

    /**
     *
     * @var integer @ORM\Column(name="nbrclicks", type="bigint", nullable=false)
     */
    protected $nbrClicks;

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
    {}
}
