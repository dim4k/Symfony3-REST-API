<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadTestData;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Tester\CommandTester;

class OrderControllerTest extends WebTestCase
{

	private $application;

	public function setUp() {
		static::$kernel = static::createKernel();
		static::$kernel->boot();

		$this->application = new Application(static::$kernel);

		// drop the database
		$command = new DropDatabaseDoctrineCommand();
		$this->application->add($command);
		$input = new ArrayInput(array(
			'command' => 'doctrine:database:drop',
			'--force' => true
		));
		$command->run($input, new NullOutput());

		// we have to close the connection after dropping the database so we don't get "No database selected" error
		$connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
		if ($connection->isConnected()) {
			$connection->close();
		}

		// create the database
		$command = new CreateDatabaseDoctrineCommand();
		$this->application->add($command);
		$input = new ArrayInput(array(
			'command' => 'doctrine:database:create',
		));
		$command->run($input, new NullOutput());

		// create schema
		$command = new CreateSchemaDoctrineCommand();
		$this->application->add($command);
		$input = new ArrayInput(array(
			'command' => 'doctrine:schema:create',
		));
		$command->run($input, new NullOutput());


		$client = static::createClient();
		$container = $client->getContainer();
		$doctrine = $container->get('doctrine');
		$entityManager = $doctrine->getManager();

		$fixture = new LoadTestData();
		$fixture->load($entityManager);
	}

	public function testGetProducts()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('GET', '/orders');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertContains('[{"id":1,"mobiles":[{"id":8,"name":"Iphone 7+","price":899},{"id":13,"name":"3410","price":39}],"customer_email":"test@testmail.com","amount":938',
			$client->getResponse()->getContent(),
			'Unexpected Json response');
	}

	public function testGetProductById()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('GET', '/orders/1');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertContains('{"id":1,"mobiles":[{"id":8,"name":"Iphone 7+","price":899},{"id":13,"name":"3410","price":39}],"customer_email":"test@testmail.com","amount":938'
			,$client->getResponse()->getContent()
			,'Unexpected Json response');
	}

	public function testPostProduct()
	{
		/***********/
		/* TEST OK */
		/***********/
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request(
			'POST',
			'/orders',
			array(),
			array(),
			array('CONTENT_TYPE' => 'application/json'),
			'{"customer_email" : "test@testmail.com","mobiles" : [{"id":1}]}'
		);

		// Test if response is OK
		$this->assertSame(201, $client->getResponse()->getStatusCode(),'Unexpected status code response ');

		/*************************/
		/* TEST BAD EMAIL FORMAT */
		/*************************/
		$client = static::createClient();
		$client->request(
			'POST',
			'/orders',
			array(),
			array(),
			array('CONTENT_TYPE' => 'application/json'),
			'{"customer_email" : "testtestmailcom","mobiles" : [{"id":1}]}'
		);

		$this->assertSame(400, $client->getResponse()->getStatusCode(),'Unexpected status code response');

		/**********************/
		/* TEST EMPTY MOBILES */
		/**********************/
		$client = static::createClient();
		$client->request(
			'POST',
			'/orders',
			array(),
			array(),
			array('CONTENT_TYPE' => 'application/json'),
			'{"customer_email" : "testtestmailcom","mobiles" : []}'
		);

		$this->assertSame(400, $client->getResponse()->getStatusCode(),'Unexpected status code response');
	}

	public function testRemoveProduct()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/orders/2');

		$this->assertSame(204, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
	}

	public function testFailRemoveProduct()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/orders/20');

		$this->assertSame(404, $client->getResponse()->getStatusCode(),'Unexpected status code response');
	}
}
