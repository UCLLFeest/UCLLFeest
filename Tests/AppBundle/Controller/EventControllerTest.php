<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 19/02/2016
 * Time: 12:22
 */

namespace Tests\AppBundle\Controller;


class EventControllerTest extends BaseWebTestCase
{
    public function login()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function testShowEvents()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/events/all');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Overview")')->count());
    }

    public function testShowEventsByUsingNavigationBar()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');
        $link = $crawler
            ->filter('a:contains("Events")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Alle Events")')->count());
    }

    public function testShowEventsMyEvents()
    {
        $client = $this->login();
        $crawler = $client->request('GET','/events');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

   public function testShowMyEventsByUsingNavigationBar()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');
        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn events")')->count());
    }

    public function testDontGiveAccessToMyEventsWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/events');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
		$crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Username")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

    public function testShowEventsInNavigationBar()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Events")')->count());
    }

    public function testAddAnEvent()
    {
        $client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);
		$count = $crawler->filter('.fa-times')->count();

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
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

      public function testAddAnEventWithoutName()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');

        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler
            ->filter('a:contains("Maak een nieuw event aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = '';
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

        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());
    }

    public function testAddAnEventWithoutAdress()
    {
		$client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = '';
		$form['event_information[city]'] = 'test';
		$form['event_information[postalCode]'] = '1234';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$crawler = $client->submit($form);

		$this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());
    }

    public function testAddAnEventWithoutCity()
    {
		$client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = 'test';
		$form['event_information[city]'] = '';
		$form['event_information[postalCode]'] = '1234';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$crawler = $client->submit($form);

		$this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());
    }

    public function testAddAnEventWithoutPostalCode()
    {
		$client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = 'test';
		$form['event_information[city]'] = 'test';
		$form['event_information[postalCode]'] = '';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$crawler = $client->submit($form);

		$this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());
    }

    public function testAddAnEventWithTooLongPostalCode()
    {
		$client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = 'test';
		$form['event_information[city]'] = 'test';
		$form['event_information[postalCode]'] = '12345';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$crawler = $client->submit($form);

		$this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());

    }

    public function testAddAnEventWithTooSmallPostalCode()
    {
		$client =  $this->login();
		$crawler = $client->request('GET','/');

		$link = $crawler
			->filter('a:contains("Account")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$link = $crawler
			->filter('a:contains("Maak een nieuw event aan")')
			->eq(0)
			->link();

		$crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'test';
		$form['event_information[adress]'] = 'test';
		$form['event_information[city]'] = 'test';
		$form['event_information[postalCode]'] = '123';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

		$crawler = $client->submit($form);

		$this->assertGreaterThan(0, $crawler->filter('html:contains("Event Information")')->count());
    }

    public function testDeleteExistingEventWhenLoggedIn()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');

        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

		$count = $crawler->filter('.fa-times')->count();

        $link = $crawler
            ->filter('a[title="Delete event"]')
            ->eq(0)
            ->link();

        $client->click($link);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
		$crawler = $client->followRedirect();
        $this->assertEquals($count-1, $crawler->filter('.fa-times')->count());

    }

    public function testDontDeleteWhenNotLoggedIn()
    {
        $client = static::createClient();

        $client->request('GET','/events/delete/0');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
		$crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testDontDeleteWhenEventNotFound()
    {
        $client =  $this->login();
        $client->request('GET','/events/delete/0');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
		$crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Couldn\'t find the event")')->count());
    }


	public function testUpdateTitleEvent()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');

        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $text = $crawler->filter('td > a')->eq(0)->text();

		$link = $crawler
			->filter('a[title="Edit information"]')
			->eq(0)
			->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'aangepast';
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
        $crawler = $client->followRedirect();

        $this->assertNotEquals($text, $crawler->filter('td > a')->eq(0)->text());
    }

    public function testShowDetails()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');

        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertEquals(1, $crawler->filter('div[class="title"]:contains("Event")')->count());
    }

    public function testUpdateEvent()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/');

        $link = $crawler
            ->filter('a:contains("Account")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);



        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

		list($name, $address, $description) = static::getOptionalNodes($crawler, 'td', 'id', array(
			"name", "address", "description"
		));

        $link = $crawler
            ->filter('a[title="Edit"]')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'aangepast2';
		$form['event_information[adress]'] = 'Drogenhof41';
		$form['event_information[city]'] = 'Leuven';
		$form['event_information[postalCode]'] = '4231';
		$form['event_information[date][date][year]'] = '2012';
		$form['event_information[date][date][month]'] = '5';
		$form['event_information[date][date][day]'] = '10';
		$form['event_information[date][time][hour]'] = '20';
		$form['event_information[date][time][minute]'] = '30';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla2";
		$form['event_information[description]'] = 'aangepast';

        $client->submit($form);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

		list($name2, $address2, $description2) = static::getOptionalNodes($crawler, 'td', 'id', array(
			"name", "address", "description"
		));

        $this->assertNotEquals($name,  $name2);
        $this->assertNotEquals($address,  $address2);
        $this->assertNotEquals($description, $description2);
    }

    public function testAddEventWithAdressThatCannotBeFound()
    {
        $client = $this->login();
        $crawler = $client->request('GET','/events/add');

		$form = $crawler->selectButton('event_information[save]')->form();
		$form['event_information[name]'] = 'jfdsmq';
		$form['event_information[adress]'] = 'jfdmsdfjs';
		$form['event_information[city]'] = 'jmmqdsj';
		$form['event_information[postalCode]'] = '1234';
		$form['event_information[date][date][year]'] = '2011';
		$form['event_information[date][date][month]'] = '11';
		$form['event_information[date][date][day]'] = '1';
		$form['event_information[date][time][hour]'] = '14';
		$form['event_information[date][time][minute]'] = '5';
		$form['event_information[foto][file][file]'] = __FILE__ . "bla";
		$form['event_information[description]'] = 'test';

        $client->submit($form);
		$crawler = $client->followRedirect();

		$notice = trim($crawler->filter('div[class="flash-notice"]')->eq(0)->text());

        $this->assertEquals("Couldn't find your adress, Please give a valid adress", $notice);
    }
}
