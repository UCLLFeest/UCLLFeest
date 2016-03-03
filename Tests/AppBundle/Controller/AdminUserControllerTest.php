<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 1/03/2016
 * Time: 11:14
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class AdminUserControllerTest extends WebTestCase
{
    private $user;
    private $admin;
    private $super;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->user = Array('username'=>'user','password'=>'test');
        $this->admin= Array('username'=>'admin','password'=>'test');
        $this->super=Array('username'=>'super','password'=>'test');
    }


    public function login($user)
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => $user['username'],
            'PHP_AUTH_PW'   => $user['password'],
        ));
    }

    public function testAdminUserOverviewWhenLoggedInAndAdministrator()
    {
        $client = $this->login($this->admin);
        $crawler = $client->request('GET','/admin/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin User Overview")')->count());
    }

    public function testAdminUserOverviewWhenLoggedInAndNotAdminstrator()
    {
        /**
         * @var Client $client
         */
        $client = $this->login($this->user2);
        $client->request('GET','/admin/user');
        $client = $this->login($this->user);
        $client->request('GET','/admin/user');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("LOG IN")')->count());
    }

    public function testAdminUserOverviewWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserProfileWhenLoggedInAndAdministrator()
    {
        $client = $this->login($this->admin);
        $crawler = $client->request('GET','/admin/user/view/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Profile:")')->count());
    }

    public function testAdminUserProfileWhenLoggedInAndNotAdministrator()
    {
        $client = $this->login($this->user);
        $client->request('GET','/admin/user/view/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("LOG IN")')->count());
    }

    public function testAdminUserProfileWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/admin/user/view/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testAdminUserProfileWhenUserDoesNotExist()
    {
        $client = $this->login($this->admin);
        $client->request('GET','/admin/user/view/0');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("does not exist")')->count());
    }

    public function testAdminUserAddRollWhenLoggedInAndSuperAdministrator()
    {
        $client = $this->login($this->super);
        $crawler = $client->request('GET','/admin/user/addrole/1');
        $form = $crawler->selectButton('Add')->form(array(
            'form[role]'  => '2',
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Role ROLE_ADMIN added")')->count());
    }

    public function testAdminUserAddRollWhenLoggedInAndSuperAdministratorButUserDoesntExist()
    {
        $client = $this->login($this->super);
        $client->request('GET','/admin/user/addrole/0');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The user with id 0 does not exist")')->count());
    }

    public function testAdminUserAddRollToUserThatHasAllRolls()
    {
        $client = $this->login($this->super);
        $client->request('GET','/admin/user/addrole/2');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("There are no roles left to add to the user")')->count());
    }

    public function testAdminUserAddRollWhenNoSuperAdmin()
    {
        $client = $this->login($this->admin);
        $client->request('GET','/admin/user/addrole/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("")')->count());
    }

    public function testAdminUserRemoveRollWhenLoggedInAndSuperAdministratorButUserDoesntExist()
    {
        $client = $this->login($this->super);
        $client->request('GET','/admin/user/removerole/0/ROLE_ADMIN');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The user with id 0 does not exist")')->count());
    }

    public function testAdminUserRemoveRollWhenNoSuperAdmin()
    {
        $client = $this->login($this->admin);
        $client->request('GET','/admin/user/removerole/1/ROLE_ADMIN');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("")')->count());
    }


    public function testAdminUserRemoveRollWhenLoggedInAndSuperAdministrator()
    {
        $client = $this->login($this->super);
        $crawler = $client->request('GET','/admin/user/view/1');
        $link = $crawler
            ->filter('a:contains("Remove Role")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Role ROLE_ADMIN removed")')->count());
    }

}
