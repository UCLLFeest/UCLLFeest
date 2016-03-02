<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 29/02/2016
 * Time: 15:55
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

class AdminControllerTest extends WebTestCase
{
    private $user;
    private $user2;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
      $this->user = Array('username'=>'test','password'=>'test');
        $this->user2= Array('username'=>'test2','password'=>'test');
    }

    public function login(Client $client, array $user)
    {
        $crawler = $client->request('GET','/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => $user['username'],
            '_password'  => $user['password'],
        ));
        $client->submit($form);
        return $client;
    }

    public function testAdminPanelWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminPanelWhenLoggedInButNotAdministrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user2);
        $client->request('GET','/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

		/**
		 * @var Crawler $crawler
		 */
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminPanelWhenLoggedInAndAdminstrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user);
        $crawler = $client->request('GET','/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin Overview")')->count());
    }
}
