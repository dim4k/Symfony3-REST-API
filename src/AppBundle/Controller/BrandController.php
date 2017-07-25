<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
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
 * Brand controller.
 *
 */
class BrandController extends Controller
{
	/**
	 * @Rest\View(serializerGroups={"brand"})
	 * @Get("/brands")
	 */
	public function getBrandsAction(Request $request)
	{
		$brands = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Brand')
			->findAll();

		/* @var $brands Brand[] */
		return $brands;
	}

	/**
	 * @Rest\View(serializerGroups={"brand"})
	 * @Get("/brands/{id}")
	 */
	public function getBrandAction(Request $request)
	{
		$brand = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Brand')
			->find($request->get('id'));

		/* @var $brand Brand */
		if (empty($brand)) {
			return new JsonResponse(['message' => 'Brand not found'], Response::HTTP_NOT_FOUND);
		}

		return $brand;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"brand"})
	 * @Rest\Post("/brands")
	 */
	public function postBrandsAction(Request $request)
	{
		$brand = new Brand();
		$brand->setName($request->get('name'));

		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($brand);
		$em->flush();

		return $brand;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/brands/{id}")
	 */
	public function removeBrandAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$brand = $em->getRepository('AppBundle:Brand')
			->find($request->get('id'));

		/* @var $place Brand */
		$em->remove($brand);
		$em->flush();
	}
}
