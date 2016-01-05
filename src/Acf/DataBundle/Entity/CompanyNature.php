<?php

namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CompanyNature
 * @ORM\Table(name="acf_company_natures")
 * @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\CompanyNatureRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"label", "company"}, errorPath="label", groups={"label"})
 */
class CompanyNature
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
	 * @var Company @ORM\ManyToOne(targetEntity="Company", inversedBy="companyNatures", cascade={"persist"})
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
	 *      })
	 */
	protected $company;

	/**
	 *
	 * @var string @ORM\Column(name="label", type="text", nullable=false)
	 */
	protected $label;

	/**
	 *
	 * @var string @ORM\Column(name="color", type="text", nullable=false)
	 */
	protected $color;

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
	 * @var Collection @ORM\OneToMany(targetEntity="Transaction", mappedBy="nature", cascade={"persist", "remove"})
	 *      @ORM\OrderBy({"number" = "ASC"})
	 */
	protected $transactions;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->color = '97BBCD';
		$this->dtCrea = new \DateTime('now');
		$this->transactions = new ArrayCollection();
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
	 * Get company
	 *
	 * @return Company
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * Set company
	 *
	 * @param Company $company
	 *
	 * @return CompanyNature
	 */
	public function setCompany(Company $company = null)
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return CompanyNature
	 */
	public function setLabel($label)
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * Get color
	 *
	 * @return string
	 */
	public function getColor()
	{
		$color = str_replace("#", "", $this->color);
		return '#' . $color;
	}

	/**
	 * Set color
	 *
	 * @param string $color
	 *
	 * @return CompanyNature
	 */
	public function setColor($color)
	{
		$color = str_replace("#", "", $color);
		$this->color = $color;

		return $this;
	}

	/**
	 * Get color
	 *
	 * @return string
	 */
	public function getColorRGB()
	{
		$hex = str_replace("#", "", $this->color);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = array('r' => $r, 'g' => $g, 'b' => $b);
		// return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
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
	 * @return CompanyNature
	 */
	public function setDtCrea(\DateTime $dtCrea)
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
	 * @return CompanyNature
	 */
	public function setDtUpdate(\DateTime $dtUpdate)
	{
		$this->dtUpdate = $dtUpdate;

		return $this;
	}

	/**
	 * Add transaction
	 *
	 * @param Transaction $transaction
	 *
	 * @return CompanyNature
	 */
	public function addTransaction(Transaction $transaction)
	{
		$this->transactions[] = $transaction;

		return $this;
	}

	/**
	 * Remove transaction
	 *
	 * @param Transaction $transaction
	 *
	 * @return CompanyNature
	 */
	public function removeTransaction(Transaction $transaction)
	{
		$this->transactions->removeElement($transaction);

		return $this;
	}

	/**
	 * Get transactions
	 *
	 * @return ArrayCollection
	 */
	public function getTransactions()
	{
		return $this->transactions;
	}

	/**
	 *
	 * @param Collection $transactions
	 *
	 * @return CompanyNature
	 */
	public function setTransactions(Collection $transactions)
	{
		$this->transactions = $transactions;

		return $this;
	}

	public function __clone()
	{
	}
}
