<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\ProductType;

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
		return $products;
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

		return $product;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"product"})
	 * @Rest\Post("/products")
	 */
	public function postProductsAction(Request $request)
	{
		$product = new Product();
		$form = $this->createForm(ProductType::class, $product);
		$product->setName($request->get('name'));
		$product->setPrice($request->get('price'));

		$brand = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Brand')
			->findOneBy($request->get('brand'));

		/* @var $brand Brand */
		if (empty($brand)) {
			return new JsonResponse(['message' => 'Brand not found'], Response::HTTP_NOT_FOUND);
		}
		$product->setBrand($brand);

		$request->request->set('brand',$brand);

		$form->submit($request->request->all());

		if($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($product);
			$em->flush();
		}else{
			return $form;
		}

		return $product;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/products/{id}")
	 */
	public function removeProductAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$product = $em->getRepository('AppBundle:Product')
			->find($request->get('id'));

		/* @var $place Product */
		$em->remove($product);
		$em->flush();
	}
}
