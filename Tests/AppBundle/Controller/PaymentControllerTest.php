<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 2/03/2016
 * Time: 13:53
 */

namespace Tests\AppBundle\Controller;


use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    public function login()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'test',
        ));
    }

    public function testShowEvents()
    {
        /**
         * @var Client $client
         */
        $client = $this->login();

		/**
		 * @var Crawler $crawler
		 */
        $crawler = $client->request('GET','/order/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Event Overview")')->count());
    }
}
