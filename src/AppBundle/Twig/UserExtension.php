<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Gender;

class UserExtension extends \Twig_Extension
{
    public function getName()
    {
        return "UserExtension";
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('genderToString', array($this, 'genderToString'))
        );
    }

    public function genderToString($gender)
    {
        return Gender::getPrettyName($gender);
    }
}