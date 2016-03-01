<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 15:45
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleTest extends WebTestCase
{
    /**
     * @var Role
     */
    private $role;

	/**
	 * @var Role
	 */
    private $required;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->role = new Role();
        $this->required = new Role();
        $this->required->setName("Required");
    }

    public function testSetName()
    {
        $this->role->setName("test");
        $this->assertEquals("test", $this->role->getName());
    }

    public function testSetRequiredRole()
    {
        $this->role->setRequiredRole($this->required);
        $this->assertEquals($this->required->getName(), $this->role->getRequiredRole()->getName());
    }

    public function testSetRequiredRoleWithSameRole()
    {
        $this->role->setRequiredRole($this->role);
        $this->assertEquals($this->role, $this->role->getRequiredRole());
    }

    public function testSetLocked()
    {
        $this->role->setLocked(true);
        $this->assertEquals(true, $this->role->isLocked());
    }

    public function testSetMandatory()
    {
        $this->role->setMandatory(true);
        $this->assertEquals(true, $this->role->isMandatory());
    }


}
