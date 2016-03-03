<?php

/**
 * Class DashboardController
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cntroller for the user's dashboard.
 * @package AppBundle\Controller
 */
class DashboardController extends Controller
{
	/**
	 * Displays the user's dashboard.
	 * @Route("/dashboard", name="dashboard")
	 * @return Response
	 */
    public function dashboard()
    {
        //Alle evenementen worden opgezocht en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();

        //$tickets = $em->createQuery("Select t from AppBundle:Event e inner join AppBundle:Ticket t where e.id = t.event and e.creator = :creator ")->setParameter('creator',  $user->getId())->getResult();
        //$sold = $em->createQuery("Select t from AppBundle:Event e inner join AppBundle:Ticket t where e.id = t.event and e.creator = :creator ")->setParameter('creator',  $user->getId())->getResult();

		/**
		 * @var EventRepository $repo
		 */
		$repo = $em->getRepository('AppBundle:Event');

        /**
         * @var Event $event
         */
		/** @noinspection PhpUndefinedMethodInspection */
		$events = $repo->findByCreator($user);

        $tickets = new ArrayCollection();
        $totaaltickets = 0;
        $totaalprijs = 0.0;

        foreach($events as $event) {
            if($event->getSelling()){
                $totaalprijs += $event->getPrice() * $event->getTickets()->count();
                $totaaltickets += $event->getTickets()->count();
                foreach($event->getTickets() as $ticket) {
                    $tickets->add($ticket);
                }
            }
        }

        return $this->render('dashboard/dashboard.html.twig',array('events'=>$events, 'tickets'=>$tickets, 'totaaltickets'=>$totaaltickets, 'totaalprijs' => number_format($totaalprijs, 2)));
    }

    /**
	 * Displays information about an event to the user that created it.
     * @Route("dashboard/{id}", name="dashboard_event")
     * @param integer $id Event id.
     * @return RedirectResponse|Response
     */
    public function dashboardEvent($id)
    {
        $em =$this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if ($event && $event->getCreator() == $this->getUser()) {
            $tickets = new ArrayCollection();
            $totaaltickets = 0;
            $totaalprijs = 0.0;

            if($event->getSelling()){
                $totaalprijs += $event->getPrice() * $event->getTickets()->count();
                $totaaltickets += $event->getTickets()->count();
                foreach($event->getTickets() as $ticket) {
                    $tickets->add($ticket);
                }
            }

            return $this->render('dashboard/dashboard_event.html.twig',array('event'=>$event, 'tickets'=>$tickets, 'totaaltickets'=>$totaaltickets, 'totaalprijs' => number_format($totaalprijs, 2)));
        } else {
            $this->addFlash('notice', "Couldn't find that Dashboard!");
            return $this->redirectToRoute('dashboard');
        }


    }

}