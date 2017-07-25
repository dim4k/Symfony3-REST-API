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
		$formatted = [];
		foreach ($orders as $order) {
			$formatted[] = [
				'id' => $order->getId(),
				'brand' => $order->getMobiles(),
				'name' => $order->getCustomer_email(),
				'price' => $order->getAmount(),
				'created' => $order->getCreated(),
			];
		}

		return new JsonResponse($formatted);
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
		if (empty($order)) {
			return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
		}

		$formatted[] = [
			'id' => $order->getId(),
			'brand' => $order->getMobiles(),
			'name' => $order->getCustomer_email(),
			'price' => $order->getAmount(),
			'created' => $order->getCreated(),
		];

		return new JsonResponse($formatted);
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

		$product = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Product')
			->find($request->get('product'));

		$order->addMobile($product);

		$em = $this->get('doctrine.orm.entity_manager');
		$em->persist($order);
		$em->flush();

		$formatted[] = [
			'id' => $order->getId(),
			'brand' => $order->getMobiles(),
			'name' => $order->getCustomer_email(),
			'price' => $order->getAmount(),
			'created' => $order->getCreated(),
		];

		return $formatted;
	}
}
