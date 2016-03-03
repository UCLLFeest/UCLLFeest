<?php

/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 19/02/2016
 * Time: 12:53
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Payment as BasePayment;

/**
 * This class represents a payment, whether in progress or completed, that a user has performed.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Payment extends BasePayment
{
    /**
     * @var integer id.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
