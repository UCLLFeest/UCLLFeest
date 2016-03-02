<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Event;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * This class represents a user. It contains information like the user name, password (encrypted), email address, and so forth.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @var string This constant is used to identify the admin role.
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var integer id.
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string The user's first name.
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string The user's last name.
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @var integer The user's gender.
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @var \DateTime The user's birthday.
     *
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $birthday;

    /**
     * @var ArrayCollection The list of events that this user can manage.
     *
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="managers", indexBy="id")
     * @ORM\JoinTable(name="app_managers")
     */
    private $managing;

    /**
     * @var ArrayCollection The list of events that this user has created.
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="creator")
     */
    private $events;

    /**
     * @var ArrayCollection The list of tickets that this user has bought.
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="owner")
     */
    private $tickets;

    public function __construct()
    {
        parent::__construct();

        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->managing = new ArrayCollection();
    }

    /**
     * Erases login credentials.
     */
    public function eraseCredentials()
    {
		parent::eraseCredentials();
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
     * Adds an event to the list of events.
     *
     * @param Event $event
     * @return User $this
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);

        return $this;
    }

	/**
	 * Removes an event from the list of events.
	 *
	 * @param Event $event
	 * @return User $this
	 */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);

		return $this;
    }

    /**
     * Gets the list of events.
     *
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Gets the user's first name.
     * @return string first name
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Sets the user's first name.
     * @param string $firstname The user's first name
     * @return User $this
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets the user's last name.
     * @return string last name
     */
    public function getLastName()
    {
        return $this->lastname;
    }

	/**
	 * Gets the user's full name.
	 * @return string
	 */
    public function getFullName()
    {
        $result = "";

        if ($this->firstname != "")
        {
            $result .= $this->firstname;

            if($this->lastname != "")
                $result .= ' ';
        }

        if($this->lastname != "")
            $result .= $this->lastname;

        return $result;
    }


    /**
     * Sets the user's last name.
     * @param string $lastname The user's last name
     * @return User $this
     */
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the user's gender.
     * @return integer gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the user's gender.
     * @param integer $gender the user's gender
     * @return User $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Returns the user's birthday.
     * @return \DateTime birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Sets the user's birthday.
     * @param \DateTime $birthday
     * @return User $this
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Adds a ticket to the list of tickets.
     *
     * @param Ticket $ticket
     * @return User $this
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets->add($ticket);

        return $this;
    }

	/**
	 * Removes a ticket from the list of tickets.
	 *
	 * @param Ticket $ticket
	 * @return User $this
	 */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);

		return $this;
    }

    /**
     * Gets the list of tickets.
     *
     * @return ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Adds an event that the user can manage to the list of manageable events.
     *
     * @param Event $managing
     *
     * @return User $this
     */
    public function addManaging(Event $managing)
    {
        $this->managing->add($managing);

        return $this;
    }

	/**
	 * Removes an event that the user was managing from the list of manageable events.
	 *
	 * @param Event $managing
	 * @return User $this
	 */
    public function removeManaging(Event $managing)
    {
        $this->managing->removeElement($managing);

		return $this;
    }

    /**
     * Gets the list of manageable events.
     *
     * @return Collection
     */
    public function getManaging()
    {
        return $this->managing;
    }
}
