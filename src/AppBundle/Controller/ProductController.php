<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 *
 * Product controller.
 *
 */
class ProductController extends Controller
{
	/**
	 * @Rest\View(serializerGroups={"product"})
	 * @Get("/products")
	 */
	public function getProductsAction(Request $request)
	{
		$products = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Product')
			->findAll();

		/* @var $products Product[] */
		$formatted = [];
		foreach ($products as $product) {
			$formatted[] = [
				'id' => $product->getId(),
				'brand' => $product->getBrand(),
				'name' => $product->getName(),
				'price' => $product->getPrice()
			];
		}

		return new JsonResponse($formatted);
	}

	/**
	 * @Rest\View(serializerGroups={"product"})
	 * @Get("/products/{id}")
	 */
	public function getProductAction(Request $request)
	{
		$product = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Product')
			->find($request->get('id'));

		/* @var $product Product */
		if (empty($product)) {
			return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
		}

		$formatted[] = [
			'id' => $product->getId(),
			'brand' => $product->getBrand(),
			'name' => $product->getName(),
			'price' => $product->getPrice()
		];

		return new JsonResponse($formatted);
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"product"})
	 * @Rest\Post("/products")
	 */
	public function postProductsAction(Request $request)
	{
		$product = new Product();
		$product->setName($request->get('name'));
		$product->setPrice($request->get('price'));

		$brand = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Brand')
			->findOneBy($request->get('brand'));

		$product->setBrand($brand);

		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($product);
		$em->flush();

		$formatted = [
			'id' => $product->getId(),
			'brand' => $product->getBrand(),
			'name' => $product->getName(),
			'price' => $product->getPrice()
		];

		return $formatted;
	}
}
