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


    public function login($client)
    {
        $crawler = $client->request('GET','/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => 'test',
            '_password'  => 'test',
        ));
        $client->submit($form);
        return $client;
    }

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
        $client =  $this->login($client);

        $crawler = $client->request('GET','/events/my_events');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }

    public function testShowMyEventsByUsingNavigationBar()
    {
        $client = static::createClient();
        $client =  $this->login($client);

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
        $client =  $this->login($client);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $count = $crawler->filter('a:contains("Delete")')->count();

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
        $form['event[foto[file]'] = __FILE__;
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertEquals($count+1, $crawler->filter('a:contains("Delete")')->count());

    }

      public function testAddAnEventWithoutName()
    {
        $client = static::createClient();

        $client =  $this->login($client);

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

        $client =  $this->login($client);

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

        $client =  $this->login($client);

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
        $client =  $this->login($client);

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
        $client =  $this->login($client);

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

        $client =  $this->login($client);

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
        $client =  $this->login($client);

        $crawler = $client->followRedirect();


        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $count = $crawler->filter('a:contains("Delete")')->count();

        $link = $crawler
            ->filter('a:contains("Delete")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals($count-1, $crawler->filter('a:contains("Delete")')->count());

    }

    public function testDontDeleteWhenNotLoggedIn()
    {
        $client = static::createClient();

        $crawler = $client->request('GET','/events/delete_event/0');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testDontDeleteWhenEventNotFound()
    {
        $client = static::createClient();
        $client =  $this->login($client);



        $crawler = $client->request('GET','/events/delete_event/0');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAddEventFromVenue()
    {
        $client = static::createClient();

        $client =  $this->login($client);

        $crawler = $client->request('GET','/events/my_events');
        $count = $crawler->filter('a:contains("Delete")')->count();


        $crawler = $client->request('GET','/venues');
        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $link = $crawler->filter('a:contains("Registreer een event in deze Venue")')->link();
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

        $this->assertEquals($count+1, $crawler->filter('a:contains("Delete")')->count());
    }

   public function testUpdateTitleEvent()
    {
        $client = static::createClient();
        $client =  $this->login($client);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $text = $crawler->filter('td > a')->eq(0)->text();

        $link = $crawler
            ->filter('a:contains("Update")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'aangepast';
        $form['event[adress]'] = 'test';
        $form['event[city]'] = 'test';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertNotEquals($text, $crawler->filter('td > a')->eq(0)->text());

    }

    public function testShowDetails()
    {
        $client = static::createClient();
        $client =  $this->login($client);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertEquals(1, $crawler->filter('html:contains("Detail overview")')->count());




    }

    public function testUpdateEvent()
    {
        $client = static::createClient();
        $client =  $this->login($client);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);



        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $name = $crawler->filter('td')->eq(1)->text();
        $adress = $crawler->filter('td')->eq(3)->text();
        $prijs = $crawler->filter('td')->eq(5)->text();
        $description = $crawler->filter('td')->eq(9)->text();
        $capaciteit = $crawler->filter('td')->eq(11)->text();

        $crawler = $client->request('GET','/events/my_events');


        $link = $crawler
            ->filter('a:contains("Update")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'aangepast2';
        $form['event[adress]'] = 'Drogenhof41';
        $form['event[city]'] = 'Leuven';
        $form['event[postalCode]'] = '4231';
        $form['event[price]'] = '5';
        $form['event[description]'] = 'aangepast';
        $form['event[capacity]'] = '1';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $link = $crawler
            ->filter('td > a')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);

        $this->assertNotEquals($name,  $crawler->filter('td')->eq(1)->text());
        $this->assertNotEquals($adress,  $crawler->filter('td')->eq(3)->text());
        $this->assertNotEquals($prijs,  $crawler->filter('td')->eq(5)->text());
        $this->assertNotEquals($description, $crawler->filter('td')->eq(9)->text());
        $this->assertNotEquals($capaciteit, $crawler->filter('td')->eq(11)->text());
    }

    public function testAddEventWithAdressThatCannotBeFound()
    {
        $client = static::createClient();
        $client = $this->login($client);
        $crawler = $client->request('GET','/events/Add_Event');

        $form = $crawler->selectButton('event[save]')->form();
        $form['event[name]'] = 'jfdsmq';
        $form['event[adress]'] = 'jfdmsdfjs';
        $form['event[city]'] = 'jmmqdsj';
        $form['event[postalCode]'] = '1234';
        $form['event[price]'] = '1000';
        $form['event[description]'] = 'test';
        $form['event[capacity]'] = '500';

        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Couldn\'t find your adress, Please give a valid adress")')->count());
    }
}
