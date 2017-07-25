<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
	 * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
	 * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
	 */
	private $brand;

    /**
     * @var string
     *
	 * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
	 *
     * @Assert\NotBlank()
     * @ORM\Column(name="price", type="float")
	 * @Assert\Range(
	 *      min = 0,
	 *      minMessage = "Price must be at least {{ limit }}",
	 * )
     */
    private $price;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * Set brand
	 *
	 * @param \AppBundle\Entity\Brand $brand
	 *
	 * @return Product
	 */
	public function setBrand($brand = null)
	{
		$this->brand = $brand;

		return $this;
	}

	/**
	 * Get brand
	 *
	 * @return \AppBundle\Entity\Brand
	 */
	public function getBrand()
	{
		return $this->brand;
	}

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}

