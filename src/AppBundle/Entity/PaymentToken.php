<?php

/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 19/02/2016
 * Time: 12:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * A Payum token that keeps track of payments.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table
 * @ORM\Entity
 */
class PaymentToken extends Token
{

}
