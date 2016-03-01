<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 26/02/2016
 * Time: 16:29
 */

namespace Tests\AppBundle\FormType;


use AppBundle\FormType\ChangePassword;

class ChangePasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChangePassword
     */
    private $changePassword;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->changePassword = new ChangePassword();
    }

    public function testSetPlainPassword()
    {
        $this->changePassword->setPlainPassword("test");
        $this->assertEquals("test", $this->changePassword->getPlainPassword());
    }

    public function testSetOldPassword()
    {
        $this->changePassword->setOldPassword("test");
        $this->assertEquals("test", $this->changePassword->getOldPassword());
    }



}
