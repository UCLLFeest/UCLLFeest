<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 16:08
 */

namespace Tests\AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class TicketRepositoryTest extends KernelTestCase
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

    public function testFindIfPersonHasTicket()
    {
        $tickets = $this->em
            ->getRepository('AppBundle:Ticket')
            ->findIfPersonHasTicket(1,1);
        $this->assertNotCount(0,$tickets);
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
