<?php
namespace Acf\DataBundle\Entity;

/**
 * OnlineInvoiceDocument
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineInvoiceDocument
{

    /**
     *
     * @var integer
     */
    const ST_NEW = 1;

    /**
     *
     * @var integer
     */
    const ST_OK = 2;

    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var OnlineInvoice
     */
    protected $invoice;

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
     * @var integer
     */
    protected $visible;

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
        $this->visible = self::ST_NEW;
        $this->size = 0;
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
     *
     * @return OnlineInvoice $invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     *
     * @param OnlineInvoice $invoice
     *
     * @return OnlineInvoiceDocument
     */
    public function setInvoice(OnlineInvoice $invoice)
    {
        $this->invoice = $invoice;
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
     * @return OnlineInvoiceDocument
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
     * @return OnlineInvoiceDocument
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
     * @return OnlineInvoiceDocument
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
     * @return OnlineInvoiceDocument
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
     * @return OnlineInvoiceDocument
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     *
     * @return integer $visible
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     *
     * @param integer $visible
     *
     * @return OnlineInvoiceDocument
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
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
     * @return OnlineInvoiceDocument
     */
    public function setDtCrea(\DateTime $dtCrea = null)
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
     * @return OnlineInvoiceDocument
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
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
     * Choice Form renew
     *
     * @return multitype:string
     */
    public static function choiceVisible()
    {
        return array(
            'OnlineInvoiceDocument.visible.choice.' . self::ST_NEW => self::ST_NEW,
            'OnlineInvoiceDocument.visible.choice.' . self::ST_OK => self::ST_OK
        );
    }

    /**
     * Choice Validator renew
     *
     * @return multitype:string
     */
    public static function choiceVisibleCallback()
    {
        return array(
            self::ST_NEW,
            self::ST_OK
        );
    }

    /**
     */
    public function __clone()
    {
    }
}