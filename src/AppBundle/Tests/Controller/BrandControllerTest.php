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

class BrandControllerTest extends WebTestCase
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

    public function testGetBrands()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('GET', '/brands');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'[{"id":2,"name":"Apple","products":[{"id":5,"name":"Iphone","price":99},{"id":6,"name":"Iphone 2","price":99},{"id":7,"name":"Iphone 6s","price":399},{"id":8,"name":"Iphone 7+","price":899}]},{"id":4,"name":"Nokia","products":[{"id":12,"name":"3210","price":29},{"id":13,"name":"3410","price":39}]},{"id":1,"name":"Oneplus","products":[{"id":1,"name":"Oneplus 1","price":99},{"id":2,"name":"Oneplus 2","price":299},{"id":3,"name":"Oneplus 3","price":399},{"id":4,"name":"Oneplus 5","price":499}]},{"id":3,"name":"Samsung","products":[{"id":9,"name":"Galaxy S2","price":99},{"id":10,"name":"Galaxy S5","price":299},{"id":11,"name":"Galaxy S7","price":799}]},{"id":5,"name":"Sony","products":[]}]',
			'Unexpected Json response');
	}

	public function testGetBrandById()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('GET', '/brands/1');

		$response = $client->getResponse();

		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'{"id":1,"name":"Oneplus","products":[{"id":1,"name":"Oneplus 1","price":99},{"id":2,"name":"Oneplus 2","price":299},{"id":3,"name":"Oneplus 3","price":399},{"id":4,"name":"Oneplus 5","price":499}]}',
			'Unexpected Json response');
	}

	public function testPostBrand()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request(
			'POST',
			'/brands',
			array(),
			array(),
			array('CONTENT_TYPE' => 'application/json'),
			'{"name":"Huawei"}'
		);

		$response = $client->getResponse();
		// Test if response is OK
		$this->assertSame(201, $client->getResponse()->getStatusCode(),'Unexpected status code response ');

		$client = static::createClient();
		$client->request('GET', '/brands/6');
		// Test if response is OK
		$this->assertSame(200, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
		// Test if Content-Type is valid application/json
		$this->assertSame('application/json', $response->headers->get('Content-Type'),'Unexpected content type response');
		// Test response content fetch json format and test data
		$this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(),
			'{"id":6,"name":"Huawei","products":[]}',
			'Unexpected Json response');
	}

	public function testRemoveDeleteBrand()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/brands/5');

		// Test if response is OK
		$this->assertSame(204, $client->getResponse()->getStatusCode(),'Unexpected status code response ');
	}

	public function testFailRemoveDeleteBrand()
	{
		// Create a new client to browse the application
		$client = static::createClient();
		$client->request('DELETE', '/brands/10');

		// Test if response is OK
		$this->assertSame(404, $client->getResponse()->getStatusCode(),'Unexpected status code response');
	}
}
