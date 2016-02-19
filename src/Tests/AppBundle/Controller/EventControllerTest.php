<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 19/02/2016
 * Time: 12:22
 */

namespace AppBundle\Tests\Controller;


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
        $client->followRedirects(true);
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
        $client->followRedirects(true);
        $crawler = $client->request('GET','/account/login');

        $form = $crawler->selectButton('Login')->form();
        $form['form[username]'] = 'test';
        $form['form[password]'] = 'test';

        $crawler = $client->submit($form);

        $link = $crawler
            ->filter('a:contains("Mijn Events")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Mijn Events.")')->count());
    }



}
