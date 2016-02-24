<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 24/02/2016
 * Time: 16:25
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
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

    public function testSearchEvent()
    {
        $client = static::createClient();
        $client = $this->login($client);
        $crawler = $client->followRedirect();
        $form = $crawler->selectButton('Submit')->form(array(
            'Search' => 'test'
        ));
        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('a')->count());
    }

}
