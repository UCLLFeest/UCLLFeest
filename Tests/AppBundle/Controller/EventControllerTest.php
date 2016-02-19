<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 19/02/2016
 * Time: 12:22
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{

    public function testShowEvents()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/events/allEvents');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Overview")')->count());
    }

    public function testShowEventsByUsingNavigationBar()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');
        $link = $crawler
            ->filter('a:contains("View")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Overview")')->count());
    }

    public function testShowEventsMyEvents()
    {
        $client = static::createClient();

        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);

        $crawler = $client->request('GET','/events/my_events');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

    public function testShowMyEventsByUsingNavigationBar()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

    public function testDontGiveAccessToMyEventsWhenNotLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/events/my_events');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Username")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

    public function testDontShowMijnEventsInNavigationBar()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/');
        $this->assertEquals(0, $crawler->filter('html:contains("Mijn Events")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("View")')->count());
    }

    public function testAddAnEvent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $count = $crawler->filter('html:contains("Delete")')->count();

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $this->assertEquals($count+1, $crawler->filter('html:contains("Delete")')->count());

    }

      public function testAddAnEventWithoutName()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Maak een nieuw evenement")')->count());
    }

    public function testAddAnEventWithoutAdress()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Maak een nieuw evenement")')->count());
    }

    public function testAddAnEventWithoutCity()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[adress]'] = 'test';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Maak een nieuw evenement")')->count());
    }

    public function testAddAnEventWithoutPostalCode()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Maak een nieuw evenement")')->count());
    }

    public function testAddAnEventWithTooLongPostalCode()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $count = $crawler->filter('html:contains("Delete")')->count();

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '12345';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("This value should have exactly 4 characters.")')->count());

    }

    public function testAddAnEventWithTooSmallPostalCode()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $count = $crawler->filter('html:contains("Delete")')->count();

        $link = $crawler
            ->filter('a:contains("Maak een nieuw evenement aan")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'test';
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '123';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("This value should have exactly 4 characters.")')->count());

    }



    public function testDeleteExsistingEventWhenLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $count = $crawler->filter('html:contains("Delete")')->count();

        $link = $crawler
            ->filter('a:contains("Delete")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals($count-1, $crawler->filter('html:contains("Delete")')->count());

    }

}
