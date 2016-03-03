<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 2/03/2016
 * Time: 12:42
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
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
