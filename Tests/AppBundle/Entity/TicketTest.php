<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 15:58
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Event;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Date;

class TicketTest extends \PHPUnit_Framework_TestCase
{

    private $ticket;
    private $user;
    private $event;

    protected function setUp()
    {
        $this->ticket = new Ticket();
        $this->user = new User();
        $this->user->setFirstName("Jan");
        $this->event = new Event();
        $this->event->setName("feest");
    }

    public function testSetClaimed()
    {
        $this->assertEquals(false, $this->ticket->getClaimed());

        $this->ticket->setClaimed(true);

        $this->assertEquals(true, $this->ticket->getClaimed());
    }

    public function testSetCreator()
    {
        $this->ticket->setOwner($this->user);
        $this->assertEquals($this->user->getFirstName(), $this->ticket->getOwner()->getFirstName());
    }

    public function testSetEvent()
    {
        $this->ticket->setEvent($this->event);
        $this->assertEquals($this->event->getName(), $this->ticket->getEvent()->getName());
    }

    public function testSetDate()
    {
        $this->ticket->setDate(new \DateTime());
        $this->assertEquals(new \DateTime(), $this->ticket->getDate());

    }

}
