<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 13:12
 */

namespace AppBundle\FormType;

/**
 * This class represents a search for events.
 * @package AppBundle\FormType
 */
class SearchEvents
{
    /**
     * @var string Name of the event to search for.
     */
    private $name;

	/**
	 * Gets the name of the event to search for.
	 * @return string
	 */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * Sets the name of the event to search for.
	 * @param string $name
	 */
    public function setName($name)
    {
        $this->name = $name;
    }

}