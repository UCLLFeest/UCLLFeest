<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 12:17
 */

namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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

    public function sortEventByLocationDistance($lat, $long)
    {
        $parameters = array(
            'radius' => 15,
            'lat' => $lat,
            'long' => $long,
        );

            $rsm = new ResultSetMappingBuilder($this->getEntityManager());

            $query = $this->getEntityManager()->createNativeQuery("Select *, (3959 * acos(cos
            (radians(:lat)) * cos(radians(latitude)) * cos(radians
            (longitude) - radians(:long)) + sin(radians(:lat)) * sin
            (radians(latitude)))) AS distance From app_events as j having distance < :radius order by distance desc", $rsm)->setParameters($parameters);

            $rsm->addRootEntityFromClassMetadata('AppBundle\Entity\Event', 'j');

            return $query->getResult();

    }

}