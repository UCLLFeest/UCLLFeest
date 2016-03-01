<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 16:13
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Event;
use AppBundle\Entity\Venue;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VenueTest extends WebTestCase
{
    /**
     * @var Venue
     */

    private $venue;

    /**
     * @var Event
     */
    private $event;

    protected function setUp()
    {
        $this->venue = new Venue();
        $this->event = new Event();
        $this->event->setName("Feest");
    }

    public function testSetName()
    {
        $this->venue->setName("test");

        $this->assertEquals("test", $this->venue->getName());
    }

    public function testSetAdress()
    {
        $this->venue->setAdress("test");

        $this->assertEquals("test", $this->venue->getAdress());
    }

    public function testSetCity()
    {
        $this->venue->setCity("test");

        $this->assertEquals("test", $this->venue->getCity());
    }

    public function testSetPostalCode()
    {
        $this->venue->setPostalCode("1234");

        $this->assertEquals("1234", $this->venue->getPostalCode());
    }

    public function testSetDescription()
    {
        $this->venue->setDescription("test");

        $this->assertEquals("test", $this->venue->getDescription());
    }

    public function testGetFullAdress()
    {
        $this->venue->setDescription("test");

        $this->assertEquals("test", $this->venue->getDescription());
    }

    public function testNewEventWithoutAdress()
    {

        $this->assertEquals("",$this->venue->getFullAdress());
    }

    public function testNewEventWithFullAdress()
    {

        $this->venue->setAdress("Test 20");
        $this->venue->setCity("Test");
        $this->venue->setPostalCode("1234");

        $this->assertEquals("Test 20, 1234 Test", $this->venue->getFullAdress());
    }

    public function testFullAdressWithoutAdress()
    {
        $this->venue->setCity("Test");
        $this->venue->setPostalCode("1234");

        $this->assertEquals(", 1234 Test", $this->venue->getFullAdress());
    }

    public function testFullAdressWithoutCity()
    {
        $this->venue->setAdress("Test 20");
        $this->venue->setPostalCode("1234");

        $this->assertEquals("Test 20, 1234", $this->venue->getFullAdress());
    }

    public function testFullAdressWithoutPostalCode()
    {
        $this->venue->setAdress("Test 20");
        $this->venue->setCity("Test");

        $this->assertEquals("Test 20 Test", $this->venue->getFullAdress());
    }

    public function testAddandRemoveEvent()
    {
        $this->assertEquals(0, sizeof($this->venue->getEvents()));
        $this->venue->addEvent($this->event);
        $this->assertEquals(1, sizeof($this->venue->getEvents()));
        $this->venue->removeEvent($this->event);
        $this->assertEquals(0, sizeof($this->venue->getEvents()));
    }

}
