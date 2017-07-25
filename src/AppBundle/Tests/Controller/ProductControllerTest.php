<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadProductData;
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

class ProductControllerTest extends WebTestCase
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
		$client->request('GET', '/products');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'[{"id":1,"name":"Oneplus 1","brand":{"id":1,"name":"Oneplus"},"price":99},{"id":2,"name":"Oneplus 2","brand":{"id":1,"name":"Oneplus"},"price":299},{"id":3,"name":"Oneplus 3","brand":{"id":1,"name":"Oneplus"},"price":399},{"id":4,"name":"Oneplus 5","brand":{"id":1,"name":"Oneplus"},"price":499},{"id":5,"name":"Iphone","brand":{"id":2,"name":"Apple"},"price":99},{"id":6,"name":"Iphone 2","brand":{"id":2,"name":"Apple"},"price":99},{"id":7,"name":"Iphone 6s","brand":{"id":2,"name":"Apple"},"price":399},{"id":8,"name":"Iphone 7+","brand":{"id":2,"name":"Apple"},"price":899},{"id":9,"name":"Galaxy S2","brand":{"id":3,"name":"Samsung"},"price":99},{"id":10,"name":"Galaxy S5","brand":{"id":3,"name":"Samsung"},"price":299},{"id":11,"name":"Galaxy S7","brand":{"id":3,"name":"Samsung"},"price":799},{"id":12,"name":"3210","brand":{"id":4,"name":"Nokia"},"price":29},{"id":13,"name":"3410","brand":{"id":4,"name":"Nokia"},"price":39}]',
			'Unexpected Json response');
	}

	public function testGetProductById()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('GET', '/products/1');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'{"id":1,"name":"Oneplus 1","brand":{"id":1,"name":"Oneplus"},"price":99}',
			'Unexpected Json response');
	}

	public function testPostProduct()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request(
			'POST',
			'/products',
			array(),
			array(),
			array('CONTENT_TYPE' => 'application/json'),
			'{"name":"Iphone 15", "price":"49.9", "brand":{"name":"Apple"}}'
		);

		$response = $client->getResponse();
		// Test if response is OK
		$this->assertSame(201, $client->getResponse()->getStatusCode(),'Unexpected status code response ');

		$client = static::createClient();
		$client->request('GET', '/products/14');
		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'{"id":14,"name":"Iphone 15","brand":{"id":2,"name":"Apple"},"price":49.9}',
			'Unexpected Json response');
	}

	public function testRemoveProduct()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/products/13');

		// Test if response is OK
		$this->assertSame(204, $client->getResponse()->getStatusCode(),'Unexpected status code response ');

		// We also need to check that product is remove from brand
		$client = static::createClient();
		$client->request('GET', '/brands/4');
		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'{"id":4,"name":"Nokia","products":[{"id":12,"name":"3210","price":29}]}',
			'Unexpected Json response');
	}

	public function testFailRemoveProduct()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/products/20');

		// Test if response is OK
		$this->assertSame(404, $client->getResponse()->getStatusCode(),'Unexpected status code response');
	}
}
