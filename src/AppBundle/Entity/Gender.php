<?php

/**
 * Class Gender
 */

namespace AppBundle\Entity;

use AppBundle\Util\BasicEnum;

/**
 * This is an enum of all possible values for a user's gender.
 *
 * @package AppBundle\Entity
 */
abstract class Gender extends BasicEnum
{
    const MALE = 0;
    const FEMALE = 1;

	/**
	 * Gets the list of genders as a prettied up map. Names are converted to uppercase first letter, and lowercase for the remainder.
	 *
	 * @return array
	 */
    static function getPrettyMap()
    {
        $genders = Gender::getAsMap();

        //Lowercase the keys first
        $genders = array_change_key_case($genders, CASE_LOWER);

        //Uppercase the first letter
        $gendersPrettified = array_combine(
            array_map('ucfirst', array_keys($genders)),
            array_values($genders)
        );

        return $gendersPrettified;
    }

	/**
	 * Gets the pretty name for a single gender.
	 *
	 * @param integer $gender
	 * @return string
	 */
    static function getPrettyName($gender)
    {
        $name = Gender::getName($gender);

        if($name === null)
            return "Invalid gender";

        return ucfirst(strtolower($name));
    }
}