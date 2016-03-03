<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 29/02/2016
 * Time: 12:41
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

    public function login()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function testViewAccountWhileLoggedIn()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/view/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Profile")')->count());
    }

    public function testViewAccountWhileNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/account/view/1');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testViewAccountThatDoesNotExist()
    {
        $client =  $this->login();
        $client->request('GET','/account/view/0');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("That user does not exist")')->count());
    }

    public function testViewAccountThatIsNotYours()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/view/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('h1:contains("Edit Profile")')->count());
        $this->assertEquals(0, $crawler->filter('h1:contains("Edit Password")')->count());
    }


    public function testViewAllUsersWhenLoggedIn()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/all');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Users")')->count());
    }

    public function testViewAllWhenNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/account/all');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }


    public function testEditPasswordWhenLoggedIn()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/editpassword');

        $form = $crawler->selectButton('Change Password')->form(array(
            'change_password[oldPassword]'  => 'test',
            'change_password[plainPassword][first]'  => 'test',
            'change_password[plainPassword][second]' =>'test'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Your password has been changed.")')->count());
    }

    public function testEditPasswordRepeatedPasswordIsWrong()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/editpassword');

        $form = $crawler->selectButton('Change Password')->form(array(
            'change_password[oldPassword]'  => 'test',
            'change_password[plainPassword][first]'  => 'tes',
            'change_password[plainPassword][second]' =>'test'
        ));
       $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The password fields must match.")')->count());
    }

    public function testEditPasswordOldPasswordIsWrong()
    {
        $client =  $this->login();
        $crawler = $client->request('GET','/account/editpassword');

        $form = $crawler->selectButton('Change Password')->form(array(
            'change_password[oldPassword]'  => 'tes',
            'change_password[plainPassword][first]'  => 'test',
            'change_password[plainPassword][second]' =>'test'
        ));
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Wrong value for your current password")')->count());
    }

    public function testEditPasswordNotLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET','/account/editpassword');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Log in")')->count());
    }

    public function testEditProfileChangeFirstName()
    {
        $client =  $this->login();

        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => 'tes',
            'edit_user[lastname]'  => 'test',
            'edit_user[gender]' =>'0',
            'edit_user[birthday][year]' =>'1902',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Changes saved.")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("tes")')->count());
    }


    public function testEditProfileChangeLastName()
    {
        $client =  $this->login();

        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => 'test',
            'edit_user[lastname]'  => 'testt',
            'edit_user[gender]' =>'0',
            'edit_user[birthday][year]' =>'1902',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Changes saved.")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("testt")')->count());

    }

    public function testEditProfileChangeGender()
    {
        $client =  $this->login();

        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => 'test',
            'edit_user[lastname]'  => 'test',
            'edit_user[gender]' =>'1',
            'edit_user[birthday][year]' =>'1902',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Changes saved.")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Female")')->count());
    }

    public function testEditProfileEmptyFields()
    {
        $client =  $this->login();

        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => '',
            'edit_user[lastname]'  => '',
            'edit_user[gender]' =>'1',
            'edit_user[birthday][year]' =>'1902',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Profile:")')->count());
    }



    public function testEditProfileChangeBirthday()
    {
        $client =  $this->login();

        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => 'test',
            'edit_user[lastname]'  => 'test',
            'edit_user[gender]' =>'0',
            'edit_user[birthday][year]' =>'1990',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Changes saved.")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("1990-01-01")')->count());
    }



    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $client =  $this->login();
        $crawler = $client->request('GET','/account/editprofile');
        $form = $crawler->selectButton('Save Changes')->form(array(
            'edit_user[firstname]'  => 'test',
            'edit_user[lastname]'  => 'test',
            'edit_user[gender]' =>'0',
            'edit_user[birthday][year]' =>'1902',
            'edit_user[birthday][month]' =>'1',
            'edit_user[birthday][day]' =>'1'
        ));
        $client->submit($form);

    }










}
