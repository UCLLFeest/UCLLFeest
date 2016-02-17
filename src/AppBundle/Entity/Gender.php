<?php

namespace AppBundle\Entity;

use AppBundle\Util\BasicEnum;

abstract class Gender extends BasicEnum
{
    const MALE = 0;
    const FEMALE = 1;

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

    static function getPrettyName($gender)
    {
        $name = Gender::getName($gender);

        if($name === null)
            return "Invalid gender";

        return ucfirst(strtolower($name));
    }
}