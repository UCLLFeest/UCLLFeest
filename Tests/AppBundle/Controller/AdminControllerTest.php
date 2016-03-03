<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 29/02/2016
 * Time: 15:55
 */

namespace Tests\AppBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;

class AdminControllerTest extends BaseWebTestCase
{
    private $user;
    private $user2;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
		$this->user = Array('username'=>'user','password'=>'test');
        $this->user2= Array('username'=>'admin','password'=>'test');
    }

        public function login($user)
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => $user['username'],
            'PHP_AUTH_PW'   => $user['password'],
        ));
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
        $client = $this->login($this->user);
        $client->request('GET','/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

		/**
		 * @var Crawler $crawler
		 */
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Homepagina")')->count());
    }

    public function testAdminPanelWhenLoggedInAndAdminstrator()
    {
        $client = $this->login($this->user2);
        $crawler = $client->request('GET','/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin Overview")')->count());
    }
}
