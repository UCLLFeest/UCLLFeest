<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 1/03/2016
 * Time: 11:14
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class AdminUserControllerTest extends WebTestCase
{
    private $user;
    private $user2;
    private $user3;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->user = Array('username'=>'test','password'=>'test');
        $this->user2= Array('username'=>'test2','password'=>'test');
        $this->user3=Array('username'=>'test3','password'=>'test');
    }


    public function login($client,$user)
    {
        $crawler = $client->request('GET','/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => $user['username'],
            '_password'  => $user['password'],
        ));
        $client->submit($form);
        return $client;
    }

    public function testAdminUserOverviewWhenLoggedInAndAdministrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user);
        $crawler = $client->request('GET','/admin/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin User Overview")')->count());
    }

    public function testAdminUserOverviewWhenLoggedInAndNotAdminstrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user2);
        $crawler = $client->request('GET','/admin/user');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserOverviewWhenNotLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserProfileWhenLoggedInAndAdministrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user);
        $crawler = $client->request('GET','/admin/user/view/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Profile:")')->count());
    }

    public function testAdminUserProfileWhenLoggedInAndNotAdministrator()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user2);
        $crawler = $client->request('GET','/admin/user/view/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserProfileWhenNotLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/admin/user/view/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserProfileWhenUserDoesNotExist()
    {
        $client = static::createClient();
        $client = $this->login($client,$this->user);
        $crawler = $client->request('GET','/admin/user/view/0');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("does not exist")')->count());
    }

    public function testAdminUserAddRollWhenLoggedInAndSuperAdministrator()
    {

    }

}
