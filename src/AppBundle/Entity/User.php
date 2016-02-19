<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"registration", "editpassword"})
     * @Assert\Length(max=4096)
     * Is not mapped to the database!
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

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
     * @ORM\OneToMany(targetEntity="Event", mappedBy="creator")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="owner")
     */
    private $tickets;

    public function __construct()
    {
        $this->isActive = true;

        $this->roles = new ArrayCollection();

        $this->roles->add('ROLE_USER');

        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function setRoles(ArrayCollection $roles)
    {
        $this->roles = $roles;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    public function removeRole($role)
    {
        $key = $this->roles->indexOf($role);

        if($key !== false)
        {
            $this->roles->remove($key);
        }

        return $this;
    }

    public function hasRole($role)
    {
        return $this->roles->contains($role);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized Serialized version of this class
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
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
}
