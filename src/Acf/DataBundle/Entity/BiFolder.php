<?php

namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_bifolders")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\BiFolderRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"title"}, errorPath="title", groups={"title"})
 */
class BiFolder
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
	 * @var string @ORM\Column(name="title", type="text", nullable=false)
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
	 * @var Collection @ORM\OneToMany(targetEntity="BiDoc", mappedBy="folder", cascade={"persist", "remove"})
	 *      @ORM\OrderBy({"title" = "ASC"})
	 */
	protected $docs;

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->docs = new ArrayCollection();
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
	 * @return BiFolder
	 */
	public function setTitle($title)
	{

		$this->title = $title;

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
	 * @return BiFolder
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
	 * @return BiFolder
	 */
	public function setDtUpdate($dtUpdate)
	{

		$this->dtUpdate = $dtUpdate;

		return $this;

	}

	/**
	 * Add doc
	 *
	 * @param BiDoc $doc
	 *
	 * @return BiFolder
	 */
	public function addDoc(BiDoc $doc)
	{

		$this->docs[] = $doc;

		return $this;

	}

	/**
	 * Remove doc
	 *
	 * @param BiDoc $doc
	 *
	 * @return BiFolder
	 */
	public function removeDoc(BiDoc $doc)
	{

		$this->docs->removeElement($doc);

		return $this;

	}

	/**
	 * Get docs
	 *
	 * @return ArrayCollection
	 */
	public function getDocs()
	{

		return $this->docs;

	}

	/**
	 *
	 * @param Collection $docs
	 *
	 * @return BiFolder
	 */
	public function setAddresses(Collection $docs)
	{

		$this->docs = $docs;

		return $this;

	}

	public function __toString()
	{

		return $this->getId() . ' ' . $this->getTitle();

	}

	public function __clone()
	{


	}

}
