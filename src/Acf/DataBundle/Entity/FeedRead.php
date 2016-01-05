<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_feedreads")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\FeedReadRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"url"}, errorPath="url", groups={"url"})
 */
class FeedRead
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
	 * @var string @ORM\Column(name="url", type="text", nullable=false)
	 *      @Assert\Url(checkDNS=true, groups={"url"})
	 */
	protected $url;

	/**
	 *
	 * @var bigint @ORM\Column(name="nbrdays", type="bigint", nullable=false)
	 *      @Assert\GreaterThanOrEqual(value=0, groups={"nbrDays"})
	 */
	protected $nbrDays;

	/**
	 *
	 * @var bigint @ORM\Column(name="nbritems", type="bigint", nullable=false)
	 *      @Assert\GreaterThanOrEqual(value=0, groups={"nbrItems"})
	 */
	protected $nbrItems;

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
		$this->nbrDays = 7;
		$this->nbrItems = 3;
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
	 * @return bigint
	 */
	public function getNbrDays()
	{
		return $this->nbrDays;
	}

	/**
	 *
	 * @param bigint $nbrDays
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
	 * @return bigint
	 */
	public function getNbrItems()
	{
		return $this->nbrItems;
	}

	/**
	 *
	 * @param bigint $nbrItems
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

?>
