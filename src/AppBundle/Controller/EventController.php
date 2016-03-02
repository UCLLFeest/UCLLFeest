<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 17:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\TicketRepository;
use AppBundle\Entity\Venue;
use AppBundle\Form\EventInformationType;
use AppBundle\Form\EventPaymentType;

use Doctrine\ORM\EntityManager;
use Geocoder\Exception\NoResult;
use Geocoder\Provider\GoogleMaps;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class EventController extends Controller
{
    /**
     * @Route("/events", name="show_events")
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
     * @Route("/events/all", name="show_all_events")
     */
    public function showAllEvents()
    {
        $em =$this->getDoctrine()->getManager();

        //zo niet geef aflopende datum events
        if(empty($events)) {
            //CURRENT_TIMESTAMP()
            $events = $em->createQuery('Select e from AppBundle:Event as e where e.date > CURRENT_TIMESTAMP()')
                ->getResult();
        }

        //WANNEER ER GEEN EVENTS ZIJN (mag niet gebeuren, gewoon events in het verleden tonen)
        if (empty($events)) {
            //zelfde als dit maar dan gelimit
            //$events = $em->getRepository('AppBundle:Event')->findAll();
            $events = $em->createQuery('Select e from AppBundle:Event as e')
                ->setMaxResults(20)
                ->getResult();
        }


        return $this->render('event/event_overview.html.twig',array('events'=>$events));
    }


    /**
     * @Route("/events/Add_Event", name="add_event")
     */

    //zonder venue
//    public function addEvent(Request $request)
//    {
//        //Er wordt een form gemaakt in de vorm van EventType.
//        //En wordt gekoppeld aan een Event object.
//
//
//        $event = new Event();
//        $form = $this->createForm(EventInformationType::class, $event);
//
//        $form->handleRequest($request);
//
//        // Als de form wordt gesubmit wordt er gekeken of alle values valid zijn
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Als dit klopt wordt de event aangemaakt en op de DB gezet
//            // En returnt de user naar de event overview.
//            $em = $this->getDoctrine()->getManager();
//
//
//
//            $user = $this->getUser();
//
//            $event= $this->setAdress($event);
//            $event = $this->setFoto($event,$em);
//
//            $user->addEvent($event); //MOET DIT DAN??????
//            $user->addEvent($event);
//            $event->setCreator($user);
//
//
//            $em->persist($event);
//            $em->flush();
//
//            //idk of dit moet (Dries & Sven denken van wel)
//           /* $em->persist($user);
//            $em->flush();*/
//
//            //return $this->showEvents();
//            return $this->redirectToRoute('show_events');
//        }
//
//        // De form wordt getoont
//
//        return $this->render('event/add_event.html.twig', array('form' => $form->createView()));
//        //return $this->redirectToRoute('show_tickets');
//    }


    /**
     * @Route("/events/add/{venue_id}", name="add_event_from_venue")
     */

    //met venue
 //   public function addEventFromVenue(Request $request, $venue_id)
  //  {
//       $em = $this->getDoctrine()->getManager();
//        $venue =  $em->getRepository('AppBundle:Venue')->find($venue_id);
//
//        if(!$venue) {
//            $this->addFlash('notice', "This venue doesn't exist");
//            return $this->redirectToRoute("add_event");
//        }
//
//        $event = new Event();
//        $event->setAdress($venue->getAdress());
//        $event->setPostalCode($venue->getPostalCode());
//        $event->setCity($venue->getCity());
//        $event->setVenue($venue);
//
//        $form = $this->createForm(EventType::class, $event);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//              $user = $this->getUser();
//        $event = $this->setFoto($event,$em);
//
//        $user->addEvent($event); //MOET IDT DAN???????????
//        $event->setCreator($user);
//
//        $event= $this->setAdress($event);
//
//        $em->persist($event);
//        $em->flush();
//
//            //idk of dit moet (Dries & Sven denken van wel)
//            /*$em->persist($user);
//            $em->flush(); */
//
//            //return $this->showEvents();
//            return $this->redirectToRoute('show_events');
//        }
//
//        // De form wordt getoont
//
//        //return $this->render('event/add_event.html.twig', array('form' => $form->createView()));
//        return $this->redirectToRoute('homepage');
//    }
//
//
//    /**
//     * @Route("events/update_event/{id}", name="update_event")
//     */
//
//    public function updateEvent(Request $request, $id)
//    {
//
//        //Form wordt gemaakt met event dat wordt opgehaald met id.
//        $em = $this->getDoctrine()->getManager();
//        $event = $em->getRepository('AppBundle:Event')->find($id);
//
//        if(!$event)
//        {
//            $this->addFlash('notice', "Couldn't find the event");
//            return $this->redirectToRoute('show_events');
//        }
//
//        if ($event->getCreator() == $this->getUser() || $event->getManagers()->contains($this->getUser())) {
//            $form = $this->createForm(EventInformationType::class,$event);
//            $form->handleRequest($request);
//            if ($form->isSubmitted() && $form->isValid()) {
//               $event = $this->setFoto($event,$em);
//                $event = $this->setAdress($event);
//
//                $em->persist($event);
//                $em->flush();
//
//                $em->flush();
//                // return $this->showEvents();
//                return $this->redirectToRoute('show_events');
//            }
//
//            return $this->render('event/update_event.html.twig', array('form' => $form->createView()));
//        } else {
//            $this->addFlash('notice', "You're not allowed to access this page");
//            return $this->redirectToRoute('show_events');
//        }
//    }



    //////////ADDING

	/**
	 * @Route("/events/add", name="add_event")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
     public function add(Request $request)
    {
        //Er wordt een form gemaakt in de vorm van EventType.
        //En wordt gekoppeld aan een Event object.

		/**
		 * @var Event $event
		 */
        $event = new Event();
        $form = $this->createForm(EventInformationType::class, $event);

        $form->handleRequest($request);

        // Als de form wordt gesubmit wordt er gekeken of alle values valid zijn
        if ($form->isSubmitted() && $form->isValid()) {
            // Als dit klopt wordt de event aangemaakt en op de DB gezet
            // En returnt de user naar de event overview.

			/**
			 * @var EntityManager $em
			 */
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

			/**
			 * @var Event $event
			 */
            try
            {
                $event= $this->setAdress($event);
            }catch(NoResult $e){
                $this->addFlash('notice', "Couldn't find your adress, Please give a valid adress");
                return $this->redirectToRoute("add_event");
            }
            $event = $this->setFoto($event,$em);

            $user->addEvent($event);
            $event->setCreator($user);

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('add_event_venues', array('id' => $event->getId()));
        }

        return $this->render('event/event_information.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/events/venues/{id}", name="add_event_venues")
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function venues(Request $request, $id)
    {
        //wat als event niet wordt meegegeven?
        //WAT ALS REGISTRATIE VIA VENUE ZODAT DEZE AL MOET HEIRIN STAAN??
        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

//        $form = $this->createForm(EventVenueType::class, $event);
//
//        $form->handleRequest($request);

        if ($event->getCreator() == $this->getUser()) {

            $data = array();

            $form = $this->createFormBuilder($data)
                ->add('venue', TextType::class, array('label' => 'Venue'))
                ->add('Zoek', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            $venues = array();

            if ($form->isSubmitted() && $form->isValid()) {

                //opgehaalde venue in event steken
                //event in venue steken

                //CHECKEN OF HIJ DEZE KAN VINDEN

                //eventvenuetype onnodig & twig moet gewoon een form zijn met een post

                $data = $form->getData();
                $venues = $em->createQuery("Select t from AppBundle:Venue as t where lower(t.name) LIKE lower(:name)")->setParameter('name', '%' . $data['venue'] . '%')->getResult();

                /*$venue = $em->getRepository('AppBundle:Venue')->find($data['venue']);

                if ($venue) {
                    $event->setVenue($venue);

                    $em->persist($event);
                    $em->flush();
                } else {
                    $this->addFlash('notice', "Venue could not be found.");
                }

                return $this->redirectToRoute('add_payment', array('id' => $event->getId()));*/
            }

            return $this->render('event/event_venue.html.twig', array('form' => $form->createView(), 'venues' => $venues, 'event' => $event));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }

	/**
	 * @Route("/events/venue/{id}/{venue}", name="add_event_venue")
	 * @param integer $id
	 * @param integer $venue
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function addVenue($id, $venue)
    {
        //wat als event niet wordt meegegeven?
        //WAT ALS REGISTRATIE VIA VENUE ZODAT DEZE AL MOET HEIRIN STAAN??
        $em = $this->getDoctrine()->getManager();
		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

		/**
		 * @var Venue $venue
		 */
        $venue = $em->getRepository('AppBundle:Venue')->find($venue);

        if ($event->getCreator() == $this->getUser()) {
            if ($event && $venue) {

                $event->setVenue($venue);

                $em->persist($event);
                $em->flush();


                return $this->redirectToRoute('add_payment', array('id' => $event->getId()));
            }

            return $this->redirectToRoute('add_event_venues', array('id' => $event->getId()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }

	/**
	 * @Route("/events/add/payment/{id}", name="add_payment")
	 * @param Request $request
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function addPayment(Request $request, $id)
    {

        //wat als event niet wordt meegegeven?
        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $form = $this->createForm(EventPaymentType::class, $event);

        $form->handleRequest($request);

        if ($event->getCreator() == $this->getUser()) {
            if ($form->isSubmitted() && $form->isValid()) {

                $em->persist($event);
                $em->flush();

                //naar detail of naar overview?
                return $this->redirectToRoute('profile');
            }

            return $this->render('event/event_payment.html.twig', array('form' => $form->createView()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }

    //DELETINGGG

	/**
	 * @Route("events/delete/{id}", name="delete_event")
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteEvent($id)
    {
        //De entitymanager wordt aangemaakt en verwijdert het evenement dat wordt gevonden met de id
        $em =$this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if (!$event) {
            $this->addFlash('notice', "Couldn't find the event");
            return $this->redirectToRoute('profile');
        }

        //creator GEEN MANAGERS
        if ($event->getCreator() != $this->getUser()) {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('profile');
        }

        $user = $this->getUser();
        $user->removeEvent($event);
        $em->remove($event);
        $em->flush();

        //return $this->showEvents();
        return $this->redirectToRoute('profile');
    }

    ////////////////////////////////EDITTING

	/**
	 * @Route("/events/edit/{id}", name="edit_event")
	 * @param Request $request
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function edit(Request $request, $id)
    {
        //Er wordt een form gemaakt in de vorm van EventType.
        //En wordt gekoppeld aan een Event object.
        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $form = $this->createForm(EventInformationType::class, $event);

        $form->handleRequest($request);

        if ($event->getCreator() == $this->getUser() || $event->getManagers()->contains($this->getUser())) {
            // Als de form wordt gesubmit wordt er gekeken of alle values valid zijn
            if ($form->isSubmitted() && $form->isValid()) {

			/**
			 * @var EntityManager $em
			 */
            $em = $this->getDoctrine()->getManager();

            //$user = $this->getUser();
            try{
            $event = $this->setAdress($event);
            }catch(NoResult $e) {
                $this->addFlash('notice', "Couldn't find your adress, Please give a valid adress");
                return $this->redirectToRoute("add_event");
            }
            $event = $this->setFoto($event, $em);


           /* $user->addEvent($event);


            $user->addEvent($event);
            $event->setCreator($user);*/

            $em->persist($event);
            $em->flush();

                return $this->redirectToRoute('profile');
            }

            return $this->render('event/event_information.html.twig', array('form' => $form->createView()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/events/edit/venues/{id}", name="edit_event_venues")
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function editVenues(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $data = array();

        $form = $this->createFormBuilder($data)
            ->add('venue', TextType::class, array('label' => 'Venue'))
            ->add('Zoek', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($event->getCreator() == $this->getUser()) {
            $venues = array();


            if ($form->isSubmitted() && $form->isValid()) {

                $data = $form->getData();
                $venues = $em->createQuery("Select t from AppBundle:Venue as t where lower(t.name) LIKE lower(:name)")->setParameter('name', '%' . $data['venue'] . '%')->getResult();

            }

            return $this->render('event/event_edit_venue.html.twig', array('form' => $form->createView(), 'venues' => $venues, 'event' => $event));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }

	/**
	 * @Route("/events/edit/venue/{id}/{venue}", name="edit_event_venue")
	 * @param integer $id
	 * @param integer $venueId
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function editVenue($id, $venueId)
    {
        //wat als event niet wordt meegegeven?
        //WAT ALS REGISTRATIE VIA VENUE ZODAT DEZE AL MOET HEIRIN STAAN??
        $em = $this->getDoctrine()->getManager();
		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

		/**
		 * @var Venue $venue
		 */
        $venue = $em->getRepository('AppBundle:Venue')->find($venueId);

        if ($event->getCreator() == $this->getUser()) {
            if ($event && $venue) {

                $event->setVenue($venue);

                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute('profile');
            }

            return $this->redirectToRoute('edit_event_venues', array('id' => $event->getId()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }


    /**
         * @Route("/events/edit/payment/{id}", name="edit_payment")
     */

	/**
	 * @Route("/events/edit/payment/{id}", name="edit_payment")
	 * @param Request $request
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function editPayment(Request $request, $id)
    {

        //wat als event niet wordt meegegeven?
        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $form = $this->createForm(EventPaymentType::class, $event);

        $form->handleRequest($request);

        if ($event->getCreator() == $this->getUser()) {
            if ($form->isSubmitted() && $form->isValid()) {

                $em->persist($event);
                $em->flush();

                //naar detail of naar overview?
                return $this->redirectToRoute('profile');
            }

            return $this->render('event/event_payment.html.twig', array('form' => $form->createView()));
        } else {
            $this->addFlash('notice', "You're not allowed to access this page");
            return $this->redirectToRoute('homepage');
        }
    }


    //////////////////////////

	/**
	 * @param Event $event
	 * @return Event|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function setAdress(Event $event)
    {
            $curl     = new CurlHttpAdapter();
            $geocoder = new GoogleMaps($curl);
            $adress =   $geocoder->geocode($event->getFullAdress());

        $event->setLatitude($adress->get(0)->getLatitude());
        $event->setLongitude($adress->get(0)->getLongitude());

        return $event;
    }

	/**
	 * @param Event $event
	 * @param EntityManager $em
	 * @return Event
	 */
	public function setFoto(Event $event, EntityManager $em)
    {
        $foto = $event->getFoto();
        if($foto->getFile() !== null) {
            $foto->setName($event->getName());
            $em->persist($foto);
        }
        else
            $event->setFoto(null);

        return $event;
    }


	/**
	 * @Route("events/{id}", name="event_detail")
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function eventDetail($id)
    {
        $em =$this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);
        if(!$event)
        {
            $this->addFlash('notice', "Couldn't find the event");
            return $this->redirectToRoute('show_all_events');
        }

        if ($this->getUser()) {
            $hasTicketAlready = true;

			/**
			 * @var TicketRepository $repo
			 */
			$repo = $em->getRepository('AppBundle:Ticket');

            if ($repo->findIfPersonHasTicket($event->getId(), $this->getUser()->getId()) == null) {
                $hasTicketAlready = false;
            }

            return $this->render('event/event_detail.html.twig', array('event' => $event, 'user' => $this->getUser(), 'hasTicketAlready' => $hasTicketAlready));
        } else {
            return $this->render('event/event_detail.html.twig', array('event' => $event));
        }
    }




}