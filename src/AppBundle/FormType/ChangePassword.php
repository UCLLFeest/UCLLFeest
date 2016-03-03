<?php

/**
 * Class ChangePassword
 */

namespace AppBundle\FormType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * This class represents change password form data.
 * @package AppBundle\FormType
 */
class ChangePassword
{
    /**
	 * @var string New password.
	 *
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * Is not mapped to the database!
     */
    private $plainPassword;

    /**
	 * @var string Old password.
	 *
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     * Is not mapped to the database!
     */
    private $oldPassword;

	/**
	 * Gets the new password.
	 * @return string
	 */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

	/**
	 * Sets the new password.
	 * @param string $password
	 */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

	/**
	 * Gets the old password.
	 * @return string
	 */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

	/**
	 * Sets the old password.
	 * @param string $password
	 */
    public function setOldPassword($password)
    {
        $this->oldPassword = $password;
    }
}