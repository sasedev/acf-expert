<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BulletinInfoTitle
 * @ORM\Table(name="acf_bi_titles")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BulletinInfoTitleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BulletinInfoTitle
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
	 * @var BulletinInfo @ORM\ManyToOne(targetEntity="BulletinInfo", inversedBy="titles", cascade={"persist"})
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="bi_id", referencedColumnName="id")
	 *      })
	 */
	protected $bulletinInfo;

	/**
	 *
	 * @var string @ORM\Column(name="bt_content", type="text", nullable=false)
	 *      @Assert\NotNull(groups={"title"})
	 */
	protected $title;

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
	 *
	 * @var Collection @ORM\OneToMany(targetEntity="BulletinInfoContent", mappedBy="bulletinInfoTitle", cascade={"persist", "remove"})
	 *      @ORM\OrderBy({"title" = "ASC"})
	 */
	protected $contents;

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->dtCrea = new \DateTime('now');
		$this->contents = new ArrayCollection();

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
	 * Get $bulletinInfo
	 *
	 * @return BulletinInfo
	 */
	public function getBulletinInfo()
	{

		return $this->bulletinInfo;

	}

	/**
	 * Set $bulletinInfo
	 *
	 * @param BulletinInfo $bulletinInfo
	 *
	 * @return BulletinInfoTitle
	 */
	public function setBulletinInfo(BulletinInfo $bulletinInfo)
	{

		$this->bulletinInfo = $bulletinInfo;

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
	 * @return BulletinInfoTitle
	 */
	public function setTitle($title)
	{

		$this->title = $title;

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
	 *
	 * @return BulletinInfoTitle
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
	 *
	 * @return BulletinInfoTitle
	 */
	public function setDtUpdate(\DateTime $dtUpdate)
	{

		$this->dtUpdate = $dtUpdate;

		return $this;

	}

	/**
	 * Add content
	 *
	 * @param BulletinInfoContent $content
	 *
	 * @return BulletinInfoTitle
	 */
	public function addContent(BulletinInfoContent $content)
	{

		$this->contents[] = $content;

		return $this;

	}

	/**
	 * Remove content
	 *
	 * @param BulletinInfoContent $content
	 *
	 * @return BulletinInfoTitle
	 */
	public function removeContent(BulletinInfoContent $content)
	{

		$this->contents->removeElement($content);

		return $this;

	}

	/**
	 * Get $contents
	 *
	 * @return ArrayCollection
	 */
	public function getContents()
	{

		return $this->contents;

	}

	/**
	 * Set $contents
	 *
	 * @param Collection $contents
	 *
	 * @return BulletinInfoTitle
	 */
	public function setContents(Collection $contents)
	{

		$this->contents = $contents;

		return $this;

	}

}

?>
