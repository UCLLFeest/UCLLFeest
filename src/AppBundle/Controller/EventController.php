<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 17:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Form\EventType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;


class EventController extends Controller
{
    /**
     * @Route("/events", name="show_events")
     */
    public function showEvents()
    {
        if(!$this->getUser())
        {
            throw new Exception("Gelieve in te loggen");
        }
        //Alle evenementen worden opgezogt en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();
        $event = $em->getRepository('AppBundle:Event')->findByCreator($user);

        return $this->render('event/gebruiker_event_overview.html.twig',array('events'=>$event, 'user'=>$user));
    }

    /**
     * @Route("/allEvents", name="show_all_events")
     */
    public function showAllEvents()
    {
        $em =$this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->findAll();
        return $this->render('event/event_overview.html.twig',array('events'=>$event));
    }

    /**
     * @Route("events/delete_event/{id}", name="delete_event")
     */

    public function deleteEvent($id)
    {
        if(!$this->getUser())
        {
            throw new Exception("Gelieve in te loggen");
        }
        //De entitymanager wordt aangemaakt en verwijdert het evenement dat wordt gevonden met de id
        $em =$this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException(
                'het Evenement is niet gevonden'
            );
        }

        $user = $this->getUser();
        $user->removeEvent($event);
        $em->remove($event);
        $em->flush();

        return $this->showEvents();
    }

    /**
     * @Route("/events/Add_Event", name="add_event")
     */

    public function addEvent(Request $request)
    {
        if(!$this->getUser())
        {
            throw new Exception("Gelieve in te loggen");
        }
        //Er wordt een form gemaakt in de vorm van EventType.
        //En wordt gekoppeld aan een Event object.


        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        // Als de form wordt gesubmit wordt er gekeken of alle values valid zijn
        if ($form->isSubmitted() && $form->isValid()) {
            // Als dit klopt wordt de event aangemaakt en op de DB gezet
            // En returnt de user naar de event overview.
            $user = $this->getUser();
            $user->addEvent($event);
            $event->setCreator($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->showEvents();
        }

        // De form wordt getoont
        return $this->render('event/add_event.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("events/update_event/{id}", name="update_event")
     */

    public function updateEvent(Request $request, $id)
    {
        if(!$this->getUser())
        {
            throw new Exception("Gelieve in te loggen");
        }
        //Form wordt gemaakt met event dat wordt opgehaald met id.
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->showEvents();
        }
        return $this->render('event/update_event.html.twig', array('form' => $form->createView()));
    }





}