<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 2/03/2016
 * Time: 16:09
 */

namespace Tests\AppBundle\Controller;


use Payum\Bundle\PayumBundle\Tests\Functional\WebTestCase;

class RoleControllerTest extends WebTestCase
{
    public function loginAsUser()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function loginAsAdmin()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function testShowRolesOverviewAsAdmin()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin Roles")')->count());
    }

    public function testShowRolesOverviewAsNormalUser()
    {
        $client = $this->loginAsUser();
        $client->request('GET','/admin/role');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Homepagina")')->count());
    }

    public function testShowRolesOverviewWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/admin/role');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login in")')->count());
    }

    public function testAddRoleWhenAdministrator()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role');
        $count = $crawler->filter('a:contains("Remove")')->count();
        $crawler = $client->request('GET','/admin/role/add');
        $form = $crawler->selectButton('Add')->form(array(
            'role[name]'  => 'ROLE_TEST',
            'role[requiredRole]'  => '',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan($count,$crawler->filter('a:contains("Remove")')->count());
    }

    public function testAddRoleWithNoName()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role/add');
        $form = $crawler->selectButton('Add')->form(array(
            'role[name]'  => '',
            'role[requiredRole]'  => '',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add Role")')->count());
    }

    public function testAddRoleWithExistingName()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role/add');
        $form = $crawler->selectButton('Add')->form(array(
            'role[name]'  => 'ROLE_TEST',
            'role[requiredRole]'  => '',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("A role with that name already exists")')->count());
    }

    public function testAddRoleWhenNotAdministrator()
    {
        $client = $this->loginAsUser();
        $client->request('GET','/admin/role/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Homepagina")')->count());
    }

    public function testAddRoleWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/role/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login in")')->count());
    }

    public function testEditRoleWhenAdministrator()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role/edit/4');
        $form = $crawler->selectButton('Save')->form(array(
            'role[name]'  => 'ROLE_CHANGED',
            'role[requiredRole]'  => '3',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0,$crawler->filter('a:contains("ROLE_CHANGED")')->count());
        $this->assertEquals(0,$crawler->filter('a:contains("ROLE_TEST")')->count());
    }

    public function testEditRoleWithNoName()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role/edit/4');
        $form = $crawler->selectButton('Save')->form(array(
            'role[name]'  => '',
            'role[requiredRole]'  => '',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add Role")')->count());
    }

    public function testEditRoleWithExistingName()
    {
        $client = $this->loginAsAdmin();
        $crawler = $client->request('GET','/admin/role/edit/4');
        $form = $crawler->selectButton('Save')->form(array(
            'role[name]'  => 'ROLE_TEST',
            'role[requiredRole]'  => '',
            'role[locked]' =>'0'
        ));
        $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("A role with that name already exists")')->count());
    }

    public function testEditRoleWhenNotAdministrator()
    {
        $client = $this->loginAsUser();
        $client->request('GET','/admin/role/edit/4');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Homepagina")')->count());
    }

    public function testEditRoleWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/role/edit/4');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login in")')->count());
    }

    public function testRemoveRoleAsAdministrator()
    {
        $client = $this->loginAsUser();
        $count = $crawler->filter('a:contains("Remove")')->count();
        $client->request('GET', '/admin/role/remove/4');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/admin/role');
        $this->assertEquals($count-1, $crawler->filter('html:contains("Remove")')->count());
    }

    public function testRemoveRoleAsAdministratorButDoesntExist()
    {
        $client = $this->loginAsUser();
        $client->request('GET', '/admin/role/remove/0');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/admin/role');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("No role with that id exists")')->count());
    }

}
