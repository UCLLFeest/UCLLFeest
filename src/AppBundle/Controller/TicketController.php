<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 17/02/2016
 * Time: 15:06
 */

namespace AppBundle\Controller;


use Proxies\__CG__\AppBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ticket;

class TicketController extends Controller
{
    /**
     * @Route("/tickets", name="show_tickets")
     */

    public function showTickets()
    {
        //alle tickets worden opgezocht en in een array doorgegeven naar de view
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tickets = $em->getRepository('AppBundle:Ticket')->findByOwner($user);

        return $this->render('ticket/mijn_tickets.html.twig', array('tickets' => $tickets, 'user' => $user));
    }

    /**
     * @Route("/tickets/buy/{id}", name="register_ticket")
     */

    public function register_ticket(Request $request, $id)
    {
        $ticket = new Ticket();

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $user->addTicket($ticket);
        $ticket->setOwner($user);

        $event = $em->getRepository('AppBundle:Event')->find($id);

        $ticket->setEvent($event);

        if ($em->getRepository('AppBundle:Ticket')->findIfPersonHasTicket($event->getId(), $user->getId()) == null) {
            //moet user ook niet gepersist worden?
            $em->persist($ticket);
            $em->flush();

            $em->persist($user);
            $em->flush();

            //return $this->showTickets();
        }


        return $this->redirectToRoute('show_tickets');

    }
}