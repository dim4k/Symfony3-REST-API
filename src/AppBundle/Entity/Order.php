<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
	 * @Assert\NotBlank()
	 * @ORM\ManyToMany(targetEntity="Product", cascade={"persist"})
	 */
	private $mobiles;

	/**
	 * @var string
	 *
	 * @Assert\Email(
	 *     message = "The email '{{ value }}' is not a valid email.",
	 *     checkMX = true
	 * )
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
		return $this->getCustomerEmail();
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
	 * Set setCustomerEmail
	 *
	 * @param string $customer_email
	 *
	 * @return Order
	 */
	public function setCustomerEmail($customer_email)
	{
		$this->customer_email = $customer_email;

		return $this;
	}

	/**
	 * Get getCustomerEmail
	 *
	 * @return string
	 */
	public function getCustomerEmail()
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
	public function getAmount()
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
	public function setCreated($created)
	{
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

}

