<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 23/02/2016
 * Time: 15:12
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class EventTest extends WebTestCase
{
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






}
