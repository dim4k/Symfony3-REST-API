<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use AppBundle\Entity\Brand;

class LoadProductData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$brand = new Brand();
		$brand->setName('Oneplus');
		$manager->persist($brand);

		$product = new Product();
		$product->setName('Oneplus 1');
		$product->setBrand($brand);
		$product->setPrice('99');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Oneplus 2');
		$product->setBrand($brand);
		$product->setPrice('299');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Oneplus 3');
		$product->setBrand($brand);
		$product->setPrice('399');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Oneplus 5');
		$product->setBrand($brand);
		$product->setPrice('499');
		$manager->persist($product);

		$brand = new Brand();
		$brand->setName('Apple');
		$manager->persist($brand);

		$product = new Product();
		$product->setName('Iphone');
		$product->setBrand($brand);
		$product->setPrice('99');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Iphone 2');
		$product->setBrand($brand);
		$product->setPrice('99');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Iphone 6s');
		$product->setBrand($brand);
		$product->setPrice('399');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Iphone 7+');
		$product->setBrand($brand);
		$product->setPrice('899');
		$manager->persist($product);

		$brand = new Brand();
		$brand->setName('Samsung');
		$manager->persist($brand);

		$product = new Product();
		$product->setName('Galaxy S2');
		$product->setBrand($brand);
		$product->setPrice('99');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Galaxy S5');
		$product->setBrand($brand);
		$product->setPrice('299');
		$manager->persist($product);

		$product = new Product();
		$product->setName('Galaxy S7');
		$product->setBrand($brand);
		$product->setPrice('799');
		$manager->persist($product);

		$brand = new Brand();
		$brand->setName('Nokia');
		$manager->persist($brand);

		$product = new Product();
		$product->setName('3210');
		$product->setBrand($brand);
		$product->setPrice('29');
		$manager->persist($product);

		$product = new Product();
		$product->setName('3410');
		$product->setBrand($brand);
		$product->setPrice('39');
		$manager->persist($product);

		$manager->flush();
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 1;
	}
}