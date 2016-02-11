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



class EventController extends Controller
{
    /**
     * @Route("/events", name="show_events")
     */
    public function showEvents()
    {
        //Alle evenementen worden opgezogt en in een array doorgegeven naar de view
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findAll();
        return $this->render('event/event_overview.html.twig',array('events'=>$event));
    }

    /**
     * @Route("events/delete_event/{id}", name="delete_event")
     */

    public function deleteEvent($id)
    {
        //De entitymanager wordt aangemaakt en verwijdert het evenement dat wordt gevonden met de id
        $em =$this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException(
                'het Evenement is niet gevonden'
            );
        }

        $em->remove($event);
        $em->flush();

        return $this->showEvents();
    }

    /**
     * @Route("/events/Add_Event", name="add_event")
     */

    public function AddEvent(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->showEvents();
        }

        return $this->render('event/add_event.html.twig', array('form' => $form->createView()));

    }


}