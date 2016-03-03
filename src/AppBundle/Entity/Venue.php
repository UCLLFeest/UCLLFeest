<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 20/02/2016
 * Time: 17:54
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This class represents a venue that events can take place at.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="app_venues")
 */
class Venue
{
    /**
     * @var integer id.
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Name of this venue.
     *
     * @ORM\Column(type="string",length=100)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string Address of this venue.
     *
     * @ORM\Column(type="string",length=60)
     * @Assert\NotBlank
     */
    private $adress;

    /**
     * @var string City that this venue is in.
     *
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @var string Postal code of the city that this venue is in.
     * @ORM\Column(type="string",length=4)
     * @Assert\Length(min = 4, max=4, minMessage = "The Postal code needs to be four numbers")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[0-9]\d*$/")
     */
    private $postalCode;

    /**
     * @var string An optional description of this venue.
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection List of events that take place at this venue.
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="venue")
     */
    private $events;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of the venue.
     *
     * @param string $name
     * @return Venue $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of the venue.
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets address of this venue.
     *
     * @param string $adress
     * @return Venue $this
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Gets the address of this venue.
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Sets the city that this venue is in.
     *
     * @param string $city
     * @return Venue $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets the city that this venue is in.
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the postal code of the city that this venue is in.
     *
     * @param string $postalCode
     * @return Venue $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Gets the postal code of the city that this venue is in.
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Sets the description of this venue.
     *
     * @param string $description
     * @return Venue $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the description of this venue.
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
	 * Gets the full address of this venue as a string.
	 *
     * @return string
     */
    public function getFullAdress()
    {
        $result = "";
        if($this->getAdress() != "")
        {
            $result .= $this->getAdress();
        }
        if($this->getPostalCode() != "")
        {
            $result .=", ". $this->getPostalCode();
        }
        if($this->getCity() != "")
        {
            $result .=" ". $this->getCity();
        }
        return $result;
    }

    /**
     * Adds an event to the list of events that take place at this venue.
     *
     * @param Event $event
     * @return Venue $this
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);

        return $this;
    }

	/**
	 * Removes an event from the list of events that take place at this venue.
	 *
	 * @param Event $event
	 * @return Venue $this
	 */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);

		return $this;
    }

    /**
     * Get the list of events.
     *
     * @return Collection
     */
    public function getEvents()
    {
        return $this->events;
    }
}
