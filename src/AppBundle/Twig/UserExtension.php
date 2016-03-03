<?php

/**
 * Class UserExtension
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Gender;

/** @noinspection PhpUndefinedClassInspection */

/**
 * This class adds Twig extensions related to users.
 * @package AppBundle\Twig
 */
class UserExtension extends \Twig_Extension
{
	/**
	 * Gets the name of this extension.
	 * @return string
	 */
    public function getName()
    {
        return "UserExtension";
    }

	/**
	 * Gets the list of functions that this extension has.
	 * @return array
	 */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('genderToString', array($this, 'genderToString'))
        );
    }

	/**
	 * Gets the pretty name of the given gender.
	 * @param integer $gender
	 * @return string
	 */
    public function genderToString($gender)
    {
        return Gender::getPrettyName($gender);
    }
}