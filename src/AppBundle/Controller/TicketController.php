<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 17/02/2016
 * Time: 15:06
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use AppBundle\Entity\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for ticket information.
 * @package AppBundle\Controller
 */
class TicketController extends Controller
{
	/**
	 * Shows the list of all tickets that the current user bought.
	 * @Route("/tickets", name="show_tickets")
	 * @return Response
	 */
    public function showTickets()
    {
        //alle tickets worden opgezocht en in een array doorgegeven naar de view
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        /**
         * @var TicketRepository $repo
         */
        $repo = $em->getRepository('AppBundle:Ticket');
        /** @noinspection PhpUndefinedMethodInspection */
        $tickets = $repo->findByOwner($user);

        return $this->render('ticket/mijn_tickets.html.twig', array('tickets' => $tickets, 'user' => $user));
    }

	/**
	 * Shows information about a single ticket.
	 * @Route("/ticket/{id}", name="ticket_detail")
	 * @param integer $id Ticket id.
	 * @return RedirectResponse|Response
	 */
    public function ticketDetail($id)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var Ticket $ticket
         */
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);

        if(!$ticket)
        {
            $this->addFlash('notice', "Couldn't find the Ticket");
            return $this->redirectToRoute('show_tickets');
        }

        //check ipv creator manager
        if ($ticket->getEvent()->getCreator() == $this->getUser() || $ticket->getEvent()->getManagers()->contains($this->getUser())) {
            if($ticket->getClaimed()) {
                $this->addFlash('notice', "Ticket was already claimed.");
            } else {
                $this->addFlash('notice', "Ticket has been claimed.");
                $ticket->setClaimed(true);
                $em->persist($ticket);
                $em->flush();
            }
        }

        if ($ticket->getOwner() === $this->getUser() || $ticket->getEvent()->getCreator() == $this->getUser() || $ticket->getEvent()->getManagers()->contains($this->getUser())) {
            return $this->render('ticket/ticket_detail.html.twig', array('ticket' => $ticket));
        } else {
            //zelfde error message anders weten mensen dat dit een geldig ticket is?
            $this->addFlash('notice', "Couldn't find the Ticket");
            return $this->redirectToRoute("show_tickets");
        }
	}
}