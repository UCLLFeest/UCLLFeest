<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * Is not mapped to the database!
     */
    private $plainPassword;

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     * Is not mapped to the database!
     */
    private $oldPassword;

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($password)
    {
        $this->oldPassword = $password;
    }
}