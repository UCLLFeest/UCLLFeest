<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 12:17
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findEventByName($event_name)
    {
        $parameters = array(
            'event_name' => '%'.$event_name.'%',
        );

        $query = $this->getEntityManager()->createQuery("Select t from AppBundle:Event as t where lower(t.name) LIKE lower(:event_name)")->setParameters($parameters);

        return $query->getResult();

    }
}