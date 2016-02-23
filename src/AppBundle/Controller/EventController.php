<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 17:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Venue;
use AppBundle\Form\EventType;

use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;


class EventController extends Controller
{
    /**
     * @Route("/events/my_events", name="show_events")
     */
    public function showEvents()
    {
        //Alle evenementen worden opgezocht en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();
        $events = $em->getRepository('AppBundle:Event')->findByCreator($user);

        //managed events
        $managing = $user->getManaging();

        return $this->render('event/gebruiker_event_overview.html.twig',array('events'=>$events, 'managing'=>$managing, 'user'=>$user));
    }

    /**
     * @Route("/events/allEvents", name="show_all_events")
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
        //De entitymanager wordt aangemaakt en verwijdert het evenement dat wordt gevonden met de id
        $em =$this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            $this->addFlash('notice', "Couldn't find the event");
            return $this->redirectToRoute('show_events');
        }

        //creator GEEN MANAGERS
        if ($event->getCreator() != $this->getUser()) {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('show_events');
        }

        $user = $this->getUser();
        $user->removeEvent($event);
        $em->remove($event);
        $em->flush();

        //return $this->showEvents();
        return $this->redirectToRoute('show_events');
    }

    /**
     * @Route("/events/Add_Event", name="add_event")
     */

    //zonder venue
    public function addEvent(Request $request)
    {
        //Er wordt een form gemaakt in de vorm van EventType.
        //En wordt gekoppeld aan een Event object.


        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        // Als de form wordt gesubmit wordt er gekeken of alle values valid zijn
        if ($form->isSubmitted() && $form->isValid()) {
            // Als dit klopt wordt de event aangemaakt en op de DB gezet
            // En returnt de user naar de event overview.
            $curl     = new CurlHttpAdapter();
            $geocoder = new GoogleMaps($curl);

          $adress =   $geocoder->geocode($event->getFullAdress());

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $foto = $event->getFoto();
            if($foto->getFile() !== null) {
                $foto->setName($event->getName());
                $em->persist($foto);
            }
            else
                $event->setFoto(null);
            $user->addEvent($event); //MOET DIT DAN??????
            $event->setLatitude($adress->get(0)->getLatitude());
            $event->setLongitude($adress->get(0)->getLongitude());
            $user->addEvent($event);
            $event->setCreator($user);


            $em->persist($event);
            $em->flush();

            //idk of dit moet (Dries & Sven denken van wel)
           /* $em->persist($user);
            $em->flush();*/

            //return $this->showEvents();
            return $this->redirectToRoute('show_events');
        }

        // De form wordt getoont

        return $this->render('event/add_event.html.twig', array('form' => $form->createView()));
        //return $this->redirectToRoute('show_tickets');
    }


    /**
     * @Route("/events/add/{venue_id}", name="add_event_from_venue")
     */

    //met venue
    public function addEventFromVenue(Request $request, $venue_id)
    {
        $em = $this->getDoctrine()->getManager();
        $venue =  $em->getRepository('AppBundle:Venue')->find($venue_id);

        if(!$venue) {
            $this->addFlash('notice', "This venue doesn't exist");
            return $this->redirectToRoute("add_event");
        }

        $event = new Event();
        $event->setAdress($venue->getAdress());
        $event->setPostalCode($venue->getPostalCode());
        $event->setCity($venue->getCity());
        $event->setVenue($venue);

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*$event->setAdress($venue->getAdress());
            $event->setPostalCode($venue->getPostalCode());
            $event->setCity($venue->getCity());
            $event->setVenue($venue);*/

            $user = $this->getUser();
            $foto = $event->getFoto();
            if($foto->getFile() !== null) {
                $foto->setName($event->getName());
                $em->persist($foto);
            }
            else
                $event->setFoto(null);

            $user->addEvent($event); //MOET IDT DAN???????????
            $event->setCreator($user);



            $curl     = new CurlHttpAdapter();
            $geocoder = new GoogleMaps($curl);

            $adress =   $geocoder->geocode($event->getFullAdress());
            $event->setLatitude($adress->get(0)->getLatitude());
            $event->setLongitude($adress->get(0)->getLongitude());


            $em->persist($event);
            $em->flush();

            //idk of dit moet (Dries & Sven denken van wel)
            /*$em->persist($user);
            $em->flush(); */

            //return $this->showEvents();
            return $this->redirectToRoute('show_events');
        }

        // De form wordt getoont

        return $this->render('event/add_event.html.twig', array('form' => $form->createView()));
        //return $this->redirectToRoute('show_tickets');
    }


    /**
     * @Route("events/update_event/{id}", name="update_event")
     */

    public function updateEvent(Request $request, $id)
    {

        //Form wordt gemaakt met event dat wordt opgehaald met id.
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if(!$event)
        {
            $this->addFlash('notice', "Couldn't find the event");
            return $this->redirectToRoute('show_events');
        }

        if ($event->getCreator() == $this->getUser() || $event->getManagers()->contains($this->getUser())) {
            $form = $this->createForm(EventType::class,$event);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $foto = $event->getFoto();
                if($foto->getFile() !== null)
                {
                    $em->persist($foto);
                }
                else
                    $event->setFoto(null);

                $curl     = new CurlHttpAdapter();
                $geocoder = new GoogleMaps($curl);
                $adress =   $geocoder->geocode($event->getFullAdress());
                $event->setLatitude($adress->get(0)->getLatitude());
                $event->setLongitude($adress->get(0)->getLongitude());


                $em->persist($event);
                $em->flush();

                $em->flush();
                // return $this->showEvents();
                return $this->redirectToRoute('show_events');
            }

            return $this->render('event/update_event.html.twig', array('form' => $form->createView()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('show_events');
        }
    }

    /**
     * @Route("events/event_detail/{id}", name="event_detail")
     */

    public function eventDetail($id)
    {
        $em =$this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        if(!$event)
        {
            $this->addFlash('notice', "Couldn't find the event");
            return $this->redirectToRoute('show_all_events');
        }

        if ($this->getUser()) {
            $hasTicketAlready = true;
            if ($em->getRepository('AppBundle:Ticket')->findIfPersonHasTicket($event->getId(), $this->getUser()->getId()) == null) {
                $hasTicketAlready = false;
            }

            return $this->render('event/event_detail.html.twig', array('event' => $event, 'user' => $this->getUser(), 'hasTicketAlready' => $hasTicketAlready));
        } else {
            return $this->render('event/event_detail.html.twig', array('event' => $event));
        }


    }




}