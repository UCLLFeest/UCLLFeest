<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 23/02/2016
 * Time: 17:39
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Feest', $crawler->filter('h1')->text());
    }
}
