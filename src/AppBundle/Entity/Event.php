<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 16:46
 */

//src/AppBundle/Entity/Event.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 *@ORM\Entity
 *@ORM\Table(name="app_events")
 */
class Event
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string",length=60)
     */
    private $adress;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string",length=4)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="decimal",scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string",length=20)
     */
    private $hours;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="events")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;


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
     * Set Adress
     *
     * @param string $adress
     * @return Event
     */
    public function setAdress($adress)
    {
        $this->Adress = $adress;

        return $this;
    }

    /**
     * Get Adress
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->Adress;
    }

    /**
     * Set City
     *
     * @param string $city
     * @return Event
     */
    public function setCity($city)
    {
        $this->City = $city;

        return $this;
    }

    /**
     * Get City
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->City;
    }

    /**
     * Set PostalCode
     *
     * @param string $postalCode
     * @return Event
     */
    public function setPostalCode($postalCode)
    {
        $this->PostalCode = $postalCode;

        return $this;
    }

    /**
     * Get PostalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->PostalCode;
    }

    /**
     * Set Price
     *
     * @param string $price
     * @return Event
     */
    public function setPrice($price)
    {
        $this->Price = $price;

        return $this;
    }

    /**
     * Get Price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * Set Hours
     *
     * @param string $hours
     * @return Event
     */
    public function setHours($hours)
    {
        $this->Hours = $hours;

        return $this;
    }

    /**
     * Get Hours
     *
     * @return string 
     */
    public function getHours()
    {
        return $this->Hours;
    }

    /**
     * Set Description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->Description = $description;

        return $this;
    }

    /**
     * Get Description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\User $creator
     * @return Event
     */
    public function setCreator(\AppBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
