<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 17/02/2016
 * Time: 14:36
 */

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This class represents a ticket that a user has bought for an event.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TicketRepository")
 * @ORM\Table(name="app_tickets")
 */
class Ticket
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var User The user that bought this ticket.
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="tickets")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var Event The event that this ticket was bought for.
     *
     * @ORM\ManyToOne(targetEntity="Event",inversedBy="tickets")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @var boolean Whether this ticket has been claimed or not. A claimed ticket has been checked at the event.
     * @ORM\Column(type="boolean")
     */
    private $claimed = false;


    /**
     * @var \DateTime Date and time that this ticket was bought on.
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $date;

    /**
     * Ticket constructor. Initializes the date variable to now.
     */
    public function __construct() {
        $this->date = new \DateTime();
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
     * Set claimed
     *
     * @param boolean $claimed
     * @return Ticket $this
     */
    public function setClaimed($claimed)
    {
        $this->claimed = $claimed;

        return $this;
    }

    /**
     * Get claimed
     *
     * @return boolean 
     */
    public function getClaimed()
    {
        return $this->claimed;
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return Ticket $this
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set event
     *
     * @param Event $event
     * @return Ticket $this
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Ticket $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

	/**
	 * Gets the date and time as a formatted string.
	 * @return string
	 */
    public function getDateFormatted() {
        return date_format($this->getDate(), 'd/m');
    }
}

