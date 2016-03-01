<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 16:35
 */

namespace Tests\AppBundle\Twig;


use AppBundle\Twig\UserExtension;

class UserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserExtension
     */
    private $userExtension;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->userExtension = new UserExtension();
    }

    public function testgetName()
    {
        $this->assertEquals("UserExtension", $this->userExtension->getName());
    }

    public function testGetFunctions()
    {
        $this->assertEquals(1, sizeof($this->userExtension->getFunctions()));
    }

    public function testGenderToString()
    {
        $this->assertEquals("Male", $this->userExtension->GenderToString(0));
        $this->assertEquals("Female", $this->userExtension->GenderToString(1));

    }

}
