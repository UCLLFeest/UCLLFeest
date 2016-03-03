<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 1/03/2016
 * Time: 0:52
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Symfony\Component\BrowserKit\Client;

class ManagerControllerTest extends BaseWebTestCase
{
	/**
	 * @var User
	 */
	private $manager;

	/**
	 * @var Event
	 */
	private $event;

	public function setUp()
	{
		//Ignore the user in this case
		/**
		 * @var Client $client
		 */
		list($client, $this->manager) = static::createAuthClient("user");

		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);
		$count = $crawler->filter('.fa-times')->count();

		$link = $crawler
			->filter('a:contains("Maak een nieuw evenement aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = 'test';
		$form['event_information[city]'] = 'test';
		$form['event_information[postalCode]'] = '1234';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$client->submit($form);

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$this->assertEquals($count + 1, $crawler->filter('.fa-times')->count());
	}

	public function testAddManager()
	{
		/**
		 * @var Client $client
		 * @var User $userToAdd
		 */
		list( , $userToAdd) = $this->createAuthClient("user2");

		$this->assertNotNull($userToAdd);
	}
}
