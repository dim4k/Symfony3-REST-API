<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Order;

class LoadTestData implements FixtureInterface
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

		$product2 = new Product();
		$product2->setName('Iphone 7+');
		$product2->setBrand($brand);
		$product2->setPrice('899');
		$manager->persist($product2);

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

		$product3 = new Product();
		$product3->setName('3210');
		$product3->setBrand($brand);
		$product3->setPrice('29');
		$manager->persist($product3);

		$product = new Product();
		$product->setName('3410');
		$product->setBrand($brand);
		$product->setPrice('39');
		$manager->persist($product);

		$brand = new Brand();
		$brand->setName('Sony');
		$manager->persist($brand);

		$order = new Order();
		$order->setAmount(938);
		$order->setCustomerEmail('test@testmail.com');
		$order->addMobile($product);
		$order->addMobile($product2);
		$manager->persist($order);

		$order = new Order();
		$order->setAmount(29);
		$order->setCustomerEmail('test2@testmail.com');
		$order->addMobile($product3);
		$manager->persist($order);

		$manager->flush();
	}

	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 1;
	}
}