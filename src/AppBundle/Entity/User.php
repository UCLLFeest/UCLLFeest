<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * string ROLE_ADMIN This constant is used to identify the admin role
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $birthday;

    /**
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="managers", indexBy="id")
     * @ORM\JoinTable(name="app_managers")
     */
    private $managing;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="creator")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="owner")
     */
    private $tickets;

    public function __construct()
    {
        parent::__construct();

        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function eraseCredentials()
    {
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
     * Add event
     *
     * @param Event $event
     * @return User
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Gets the user's first name
     * @return string first name
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Sets the user's first name
     * @param string $firstname The user's first name
     * @return User
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets the user's last name
     * @return string last name
     */
    public function getLastName()
    {
        return $this->lastname;
    }

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
     * Sets the user's last name
     * @param string $lastname The user's last name
     * @return User
     */
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the user's gender
     * @return integer gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the user's gender
     * @param integer $gender the user's gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Returns the user's birthday
     * @return \DateTime birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Sets the user's birthday
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     * @return User
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
     * Add managing
     *
     * @param \AppBundle\Entity\Event $managing
     *
     * @return User
     */
    public function addManaging(\AppBundle\Entity\Event $managing)
    {
        $this->managing[] = $managing;

        return $this;
    }

    /**
     * Remove managing
     *
     * @param \AppBundle\Entity\Event $managing
     */
    public function removeManaging(\AppBundle\Entity\Event $managing)
    {
        $this->managing->removeElement($managing);
    }

    /**
     * Get managing
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManaging()
    {
        return $this->managing;
    }
}
