<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 16:46
 */

namespace AppBundle\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * This class represents an event, organized by a user. The user that created the event is set as the creator.
 * The creator can add managers that can update the event.
 *
 * @package AppBundle\Entity
 *
 *@ORM\Entity
 *@ORM\Entity(repositoryClass="AppBundle\Entity\EventRepository")
 *@ORM\Table(name="app_events")
 */
class Event
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
	 * @var string Name of this event
	 *
     * @ORM\Column(type="string",length=100)
     * @Assert\NotBlank
     */
    private $name;

    /**
	 * @var string Address where the event takes place. This is the street and house number.
	 *
     * @ORM\Column(type="string",length=60)
     * @Assert\NotBlank
     */
    private $adress;

    /**
	 * @var string City where the event takes place.
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank
     */
    private $city;

    /**
	 * @var string Postal code of the city where the event takes place.
	 *
     * @ORM\Column(type="string",length=4)
     * @Assert\Length(min = 4, max=4, minMessage = "The Postal code needs to be four numbers")
     * @Assert\NotBlank
     * @Assert\Regex(pattern="/^[0-9]\d*$/")
     */
    private $postalCode;

    /**
	 * @var DateTime Date and time when the event will begin.
	 *
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $date;

    /**
	 * @var string Optional description of the event.
	 *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
	 * @var string Latitude of the event's location.
	 *
     * @ORM\Column(type="string", nullable=true)
     */
    private $latitude;

    /**
	 * @var string Longitude of the event's location.
	 *
     * @ORM\Column(type="string",nullable=true)
     */
    private $longitude;

    /**
	 * @var User The user that created this event.
	 *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="events")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
	 * @var ArrayCollection List of users that can manage this event.
	 *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="managing")
     */
    private $managers;

    /**
	 * @var Foto An optional image that can be attached to the event. Shown on the event page.
	 *
     * @ORM\OneToOne(targetEntity="Foto", mappedBy="event")
     * @ORM\JoinColumn(name="foto_id", referencedColumnName="id", nullable=true)
     */
    private $foto;

    /**
	 * @var Ticket ist of all tickets for this event that have been sold.
	 *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="event")
     */
    private $tickets;

    /**
	 * @var Venue The venue that this event takes place at. Optional.
	 *
     * @ORM\ManyToOne(targetEntity="Venue",inversedBy="events")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id", nullable=true)
     */
    private $venue;

    /**
	 * @var boolean Whether this event is selling tickets or not.
	 *
     * @ORM\Column(type="boolean")
     */
    private $selling = false;

    /**
	 * @var integer The total capacity of this event. No more tickets than this can be sold.
	 *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity = 0;

    /**
	 * @var float Price of a single ticket for this event.
	 *
     * @ORM\Column(type="decimal",scale=2, nullable=true)
     */
    private $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->setDate(new DateTime());
        $this->managers = new ArrayCollection();


        /*$this->setName("Leuk feestje in 3 stappen");
        $this->setAdress("Houwaartstraat 325");
        $this->setCity("Schoonderbuken");
        $this->setPostalCode("3270");
        $this->setDescription("HEEL LEEUK FEEST WOOEHOOW");*/

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
     * Set name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Address
     *
     * @param string $adress
     * @return Event
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get Address
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set City
     *
     * @param string $city
     * @return Event
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get City
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set PostalCode
     *
     * @param string $postalCode
     * @return Event
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get PostalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
	 * Gets the full address that this event takes place at
	 *
     * @return string full address
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
	 * Gets the date at which this event takes place as a formatted string.
	 * @return string
	 */
    public function getDateFormatted() {
        return date_format($this->getDate(), 'd/m');
    }

    /**
     * Set Price
     *
     * @param float $price
     * @return Event
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get Price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * Set Description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set creator
     *
     * @param User $creator
     * @return Event
     */
    public function setCreator(User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }


    /**
     * Set foto
     *
     * @param Foto $foto
     * @return Event
     */
    public function setFoto(Foto $foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return Foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set date
     *
     * @param DateTime $date
     * @return Event
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     * @return Event
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     * @return Event
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets->add($ticket);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set venue
     *
     * @param Venue $venue
     * @return Event
     */
    public function setVenue(Venue $venue = null)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


    /**
     * Add manager
     *
     * @param User $manager
     *
     * @return Event
     */
    public function addManager(User $manager)
    {
        $this->managers->add($manager);

        return $this;
    }

    /**
     * Remove manager
     *
     * @param User $manager
     */
    public function removeManager(User $manager)
    {
        $this->managers->removeElement($manager);
    }

    /**
     * Get managers
     *
     * @return Collection
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Set selling
     *
     * @param boolean $selling
     *
     * @return Event
     */
    public function setSelling($selling)
    {
        $this->selling = $selling;

        return $this;
    }

    /**
     * Get selling
     *
     * @return boolean
     */
    public function getSelling()
    {
        return $this->selling;
    }
}
