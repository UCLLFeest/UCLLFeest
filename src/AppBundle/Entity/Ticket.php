<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 17/02/2016
 * Time: 14:36
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_tickets")
 * @UniqueEntity("code")
 */
class Ticket
{

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="tickets")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;




    /**
     * @ORM\ManyToOne(targetEntity="Event",inversedBy="tickets")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;



    /**
     * @ORM\Column(type="boolean")
     */
    private $claimed = false;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $date;


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
     * Set code
     *
     * @param \GUID $code
     * @return Ticket
     */
    public function setCode(\GUID $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return \GUID 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set claimed
     *
     * @param boolean $claimed
     * @return Ticket
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
     * @param \AppBundle\Entity\User $owner
     * @return Ticket
     */
    public function setOwner(\AppBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     * @return Ticket
     */
    public function setEvent(\AppBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Ticket
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
}