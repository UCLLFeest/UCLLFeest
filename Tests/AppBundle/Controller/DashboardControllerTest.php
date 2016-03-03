<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 2/03/2016
 * Time: 12:42
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends BaseWebTestCase
{
    public function login()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function login2()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function setUp()
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
    }

    public function testGoToDashBoardWhenLoggedIn()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/dashboard');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Dashboard")')->count());
    }

    public function testGoToDashBoardWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/dashboard');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testGoToDashBoardEvent()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/dashboard/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Dashboard")')->count());
    }

    public function testGoToDashBoardWhenNotYourEvent()
    {
        $client =  $this->login2();
        $client->request('GET','/dashboard/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Couldn\'t find that Dashboard!")')->count());
    }

    public function testGoToDashBoardEventWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/dashboard/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }
}
