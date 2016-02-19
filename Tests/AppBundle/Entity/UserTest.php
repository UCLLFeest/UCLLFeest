<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\Event;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testNewUserHasRoleUser()
    {
        $user = new User();

        $this->assertTrue($user->hasRole('ROLE_USER'));
    }

    public function testEmptyFullName()
    {
        $user = new User();

        $this->assertEquals("", $user->getFullName());
    }

    public function testFullName()
    {
        $user = new User();

        $user->setFirstName("John");
        $user->setLastName("Smith");

        $this->assertEquals("John Smith", $user->getFullName());
    }

    public function testFullNameFirstNameOnly()
    {
        $user = new User();

        $user->setFirstName("John");

        $this->assertEquals("John", $user->getFullName());
    }

    public function testFullNameLastNameOnly()
    {
        $user = new User();

        $user->setLastName("Smith");

        $this->assertEquals("Smith", $user->getFullName());
    }

    public function testAddEvent()
    {
        $user = new User();

        $event = new Event();

        $user->addEvent($event);

        $this->assertTrue($user->getEvents()->contains($event));
    }
}