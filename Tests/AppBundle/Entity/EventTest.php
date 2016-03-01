<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 23/02/2016
 * Time: 15:12
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Event;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use AppBundle\Entity\Venue;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class EventTest extends WebTestCase
{
    /**
     * @var Event
     */
    private $event;

	/**
	 * @var User
	 */
    private $user;

	/**
	 * @var Ticket
	 */
    private $ticket;

	/**
	 * @var Venue
	 */
    private $venue;

	/**
	 * @var User
	 */
    private $manager;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->event = new Event();
        $this->user = new User();
        $this->user->setFirstName("Jan");
        $this->ticket = new Ticket();
        $this->venue = new Venue();
        $this->venue->setName("Zaal");
        $this->manager = new User();
        $this->user->setFirstName("Manager");
    }

    public function testSetName()
{
    $this->event->setName("test");

    $this->assertEquals("test", $this->event->getName());
}

    public function testSetAdress()
    {
        $this->event->setAdress("test");
        $this->assertEquals("test", $this->event->getAdress());

    }

    public function testSetCity()
    {
        $this->event->setCity("test");
        $this->assertEquals("test", $this->event->getCity());
    }

    public function testSetPostalCode()
    {
        $this->event->setPostalCode("1234");
        $this->assertEquals("1234", $this->event->getPostalCode());

    }

    public function testNewEventWithoutAdress()
    {
        $event = new Event();

        $this->assertEquals("",$event->getFullAdress());
    }

    public function testNewEventWithFullAdress()
    {
        $event = new Event();

        $event->setAdress("Test 20");
        $event->setCity("Test");
        $event->setPostalCode("1234");

        $this->assertEquals("Test 20, 1234 Test", $event->getFullAdress());
    }

    public function testFullAdressWithoutAdress()
    {
        $event = new Event();

        $event->setCity("Test");
        $event->setPostalCode("1234");

        $this->assertEquals(", 1234 Test", $event->getFullAdress());
    }

    public function testFullAdressWithoutCity()
    {
        $event = new Event();

        $event->setAdress("Test 20");
        $event->setPostalCode("1234");

        $this->assertEquals("Test 20, 1234", $event->getFullAdress());
    }

    public function testFullAdressWithoutPostalCode()
    {
        $event = new Event();

        $event->setAdress("Test 20");
        $event->setCity("Test");

        $this->assertEquals("Test 20 Test", $event->getFullAdress());
    }

    public function testSetPrice()
    {
        $this->event->setPrice("100");
        $this->assertEquals("100", $this->event->getPrice());
    }

    public function testSetDescription()
    {
        $this->event->setDescription("test");
        $this->assertEquals("test", $this->event->getDescription());
    }

    public function testSetCreator()
    {
        $this->event->setCreator($this->user);
        $this->assertEquals($this->user->getFirstName(), $this->event->getCreator()->getFirstName());

    }

    public function testSetCapacity()
    {
        $this->event->setCapacity(100);
        $this->assertEquals(100, $this->event->getCapacity());
    }

    public function testAddTicket()
    {
        $this->event->addTicket($this->ticket);
        $this->assertEquals(1, sizeof($this->event->getTickets()));
    }

    public function testRemoveTicket()
    {
        $this->event->addTicket($this->ticket);
        $this->assertEquals(1, sizeof($this->event->getTickets()));
        $this->event->removeTicket($this->ticket);
        $this->assertEquals(0, sizeof($this->event->getTickets()));
    }

    public function testSetVenue()
    {
        $this->event->setVenue($this->venue);
        $this->assertEquals("Zaal", $this->event->getVenue()->getName());
    }

    public function testSetLatitude()
    {
        $this->event->setLatitude("50.001");
        $this->assertEquals("50.001", $this->event->getLatitude());
    }

    public function testSetLongitude()
    {
        $this->event->setLongitude("4.111");
        $this->assertEquals("4.111", $this->event->getLongitude());
    }

    public function testAddandRemoveManager()
    {
        $this->event->addManager($this->manager);
        $this->assertEquals(1, sizeof($this->event->getManagers()));
        $this->event->removeManager($this->manager);
        $this->assertEquals(0, sizeof($this->event->getManagers()));
    }

    public function testSetSelling()
    {
        $this->event->setSelling(true);
        $this->assertEquals(true, $this->event->getSelling());
    }








}
