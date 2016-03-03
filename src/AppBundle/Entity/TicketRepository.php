<?php

/**
 * Class TicketRepository
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * This class is a Doctrine repository class for Ticket.
 *
 * @package AppBundle\Entity
 */
class TicketRepository extends EntityRepository
{
    /**
	 * Finds the ticket that a user has bought for a given event, if any.
     * @param integer $event_id
     * @param integer $user_id
     * @return Ticket|null
	 * @throws NonUniqueResultException
     */
    public function findIfPersonHasTicket($event_id, $user_id) {

        $parameters = array(
            'userid' =>  $user_id,
            'eventid' => $event_id
        );

       $query = $this->getEntityManager()->createQuery("SELECT t FROM AppBundle:Ticket as t where t.owner = :userid AND t.event = :eventid")->setParameters($parameters);

        return $query->getOneOrNullResult();
    }

}
