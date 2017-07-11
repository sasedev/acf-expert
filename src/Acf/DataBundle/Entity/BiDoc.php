<?php
namespace Acf\DataBundle\Entity;

/**
 * BiDoc
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BiDoc
{

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var BiFolder
     */
    protected $folder;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $fileName;

    /**
     *
     * @var integer
     */
    protected $size;

    /**
     *
     * @var string
     */
    protected $mimeType;

    /**
     *
     * @var string
     */
    protected $md5;

    /**
     *
     * @var string
     */
    protected $originalName;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var integer
     */
    protected $nbrDownloads;

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
        $this->nbrDownloads = 0;
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
     */
    public function __clone()
    {
    }
}
