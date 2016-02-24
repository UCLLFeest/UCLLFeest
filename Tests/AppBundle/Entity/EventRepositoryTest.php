<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 24/02/2016
 * Time: 15:28
 */

namespace Tests\AppBundle\Entity;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventRepositoryTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testfindEventByName()
    {
        $events = $this->em
            ->getRepository('AppBundle:Event')
            ->findEventByName('test');
        $this->assertNotCount(0,$events);
    }

    public function sortEventByLocationDistance()
    {
        $events = $this->em
            ->getRepository('AppBundle:Event')
            ->findEventByName(50.8776,4.7043);
        $this->assertNotCount(0,$events);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
    }

}
