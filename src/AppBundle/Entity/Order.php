<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToMany(targetEntity="Product", cascade={"persist"})
	 */
	private $mobiles;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="customer_email", type="string", length=255)
	 */
	private $customer_email;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount", type="float")
	 */
	private $amount;

	/**
	 * @var /Datetime
	 *
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;

	public function __construct()
	{
		$this->created = new \Datetime();
		$this->mobiles = new ArrayCollection();
	}

	public function __toString() {
		return $this->getCustomer_email();
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	public function addMobile(Product $product)
	{
		$this->mobiles[] = $product;
		return $this;
	}

	public function removeMobile(Product $product)
	{
		$this->mobiles->removeElement($product);
	}

	public function getMobiles()
	{
		return $this->mobiles;
	}

	/**
	 * Set setCustomer_email
	 *
	 * @param string $customer_email
	 *
	 * @return Order
	 */
	public function setCustomer_email($customer_email)
	{
		$this->customer_email = $customer_email;

		return $this;
	}

	/**
	 * Get getCustomer_email
	 *
	 * @return string
	 */
	public function getCustomer_email()
	{
		return $this->customer_email;
	}

	/**
	 * Set amount
	 *
	 * @param float $amount
	 *
	 * @return Order
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	/**
	 * Get amount
	 *
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->amount;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return Order
	 */
	public function setCreationDate($created)
	{
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreationDate()
	{
		return $this->created;
	}

}

