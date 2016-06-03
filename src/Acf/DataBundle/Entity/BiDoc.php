<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_bifiles")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BiDocRepository")
 *         @ORM\HasLifecycleCallbacks
 */
class BiDoc
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
     * @var BiFolder @ORM\ManyToOne(targetEntity="BiFolder", inversedBy="docs", cascade={"persist"})
     *      @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="bif_id", referencedColumnName="id")
     *      })
     */
    protected $folder;

    /**
     *
     * @var string @ORM\Column(name="title", type="text", nullable=false)
     */
    protected $title;

    /**
     *
     * @var string @ORM\Column(name="filename", type="text", nullable=false)
     *      @Assert\File(maxSize="20480k", groups={"fileName"})
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
     * Constructor
     */
    public function __construct()
    {
        $this->size = 0;
        $this->nbrDownloads = 0;
        $this->dtCrea = new \DateTime('now');
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
     * Get $folder
     *
     * @return BiFolder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set $folder
     *
     * @param BiFolder $folder
     *
     * @return BiDoc
     */
    public function setFolder(BiFolder $folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     *
     * @return BiDoc
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
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
     * @return BiDoc
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

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
