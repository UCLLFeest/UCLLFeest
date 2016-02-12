<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 12-2-2016
 * Time: 15:29
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Foto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;


    /**
     * @Vich\UploadableField(mapping="event_image", fileNameProperty="name")
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="Event", inversedBy="foto")
     */
    private $event;


    private $temp;

    /**
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
    *
    * @return Foto
    */

    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $imageName
     *
     * @return File
     */
    public function setName($imageName)
    {
        $this->name = $imageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
