<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
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
 * Order controller.
 *
 */
class OrderController extends Controller
{
	/**
	 * @Get("/orders")
	 */
	public function getOrdersAction(Request $request)
	{
		$orders = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Product')
			->findAll();

		/* @var $orders Order[] */
		return $orders;
	}

	/**
	 * @Get("/orders/{id}")
	 */
	public function getOrderAction(Request $request)
	{
		$order = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Order')
			->find($request->get('id'));

		/* @var $order Order */
		return $order;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_CREATED)
	 * @Rest\Post("/orders")
	 */
	public function postOrdersAction(Request $request)
	{
		// Created date is generate during entity construct
		$order = new Order();
		$order->setCustomer_email($request->get('customer_email'));

		foreach($request->get('mobiles') as $mobile) {
			$product = $this->get('doctrine.orm.entity_manager')
				->getRepository('AppBundle:Brand')
				->findOneBy($mobile);

			if (empty($product)) {
				return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
			}

			$order->addMobile($product);
		}

		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($order);
		$em->flush();

		return $order;
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/orders/{id}")
	 */
	public function removeOrderAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$order = $em->getRepository('AppBundle:Order')
			->find($request->get('id'));

		/* @var $place Order */
		$em->remove($order);
		$em->flush();
	}
}
