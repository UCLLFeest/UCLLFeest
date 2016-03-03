<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 12-2-2016
 * Time: 15:29
 */

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * This class represents an image that can be uploaded to the website. This image is stored on disk, with an entry in the database referring to its location.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Foto
{
    /**
     * @var integer id.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Name of this image.
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;


    /**
     * @var File The actual file that was uploaded.
     *
     * @Vich\UploadableField(mapping="event_image", fileNameProperty="name")
     * @Assert\File(maxSize="6000000")
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @var DateTime Date and time at which this image was last updated.
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var Event The event that this was uploaded for.
     *
     * @ORM\OneToOne(targetEntity="Event", inversedBy="foto")
     */
    private $event;

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
	 * Sets the image that this object represents.
	 *
     * @param File|null $image
     * @return Foto $this
     */
    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    /**
	 * Gets the image that this object represents.
	 *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
	 * Sets the name of this image.
	 *
     * @param string $imageName
     * @return File $this
     */
    public function setName($imageName)
    {
        $this->name = $imageName;

        return $this;
    }

    /**
	 * Gets the name of this string.
	 *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the time and date that this image was last updated at.
     *
     * @param DateTime $updatedAt
     * @return Foto $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the time and date that this image was last updated at.
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the event that this image belongs to.
     *
     * @param Event $event
     * @return Foto $this
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get the event that this image belongs to.
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
