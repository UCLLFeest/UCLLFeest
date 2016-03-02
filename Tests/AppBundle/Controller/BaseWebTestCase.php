<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseWebTestCase extends WebTestCase
{
	/**
	 * @param string $accountName
	 * @return Client
	 */
	public function createAuthClient($accountName)
	{
		$client = static::createClient();
		$client->getCookieJar()->set(new Cookie(session_name(), true));

		// dummy call to bypass the hasPreviousSession check
		$client->request('GET', '/');

		/**
		 * @var EntityManager $em
		 */
		$em = $client->getContainer()->get('doctrine')->getManager();

		/**
		 * @var User $user
		 */
		$user = $em->getRepository('AppBundle:User')->findOneByUsername($accountName);

		$firewall = 'main';

		$token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
		self::$kernel->getContainer()->get('security.token_storage')->setToken($token);

		$session = $client->getContainer()->get('session');
		$session->set('_security_' . $firewall, serialize($token));
		$session->save();

		return array($client, $user);
	}

	public static function getOptionalNodes(Crawler $crawler, $nodeType, $attributeKey, array $values)
	{
		$result = array();

		foreach($values as $value)
		{
			$newCrawler = $crawler->filter($nodeType . '[' . $attributeKey . '=' . '"' . $value . '"]')->eq(0);

			$result[] = $newCrawler->count() > 0 ? $newCrawler->text() : "";
		}

		return $result;
	}
}