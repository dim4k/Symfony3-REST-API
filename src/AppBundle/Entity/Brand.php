<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Brand
 *
 * @ORM\Table(name="brand")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BrandRepository")
 */
class Brand
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

	/**
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
	 */
	private $products;

	public function __construct()
	{
		$this->products = new ArrayCollection();
	}

	public function __toString() {
		return $this->getName();
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Brand
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
	 * Add product
	 *
	 * @param \AppBundle\Entity\Product $product
	 *
	 * @return Brand
	 */
	public function addProduct($product)
	{
		$this->products[] = $product;

		return $this;
	}

	/**
	 * Remove product
	 *
	 * @param \AppBundle\Entity\Product $product
	 */
	public function removeProduct($product)
	{
		$this->products->removeElement($product);
	}

	/**
	 * Get products
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProducts()
	{
		return $this->products;
	}
}

