<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 20/02/2016
 * Time: 18:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Venue;
use AppBundle\Form\VenueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class VenueController extends Controller
{

    /**
     * @Route("/venues", name="show_venues")
     */
    public function showVenues()
    {
        $em =$this->getDoctrine()->getManager();
        $venues = $em->getRepository('AppBundle:Venue')->findAll();


        return $this->render('venue/venue_overview.html.twig',array('venues'=>$venues));
    }

    /**
     * @Route("/venues/add", name="add_venue")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addVenue(Request $request)
    {
        $venue = new Venue();
        $form = $this->createForm(VenueType::class, $venue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($venue);
            $em->flush();

            return $this->redirectToRoute('show_venues');
        }

        return $this->render('venue/add_venue.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("venue/{id}", name="venue_detail")
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function venueDetail($id)
    {
        $em =$this->getDoctrine()->getManager();
        $venue = $em->getRepository('AppBundle:Venue')->find($id);

        if(!$venue)
        {
            $this->addFlash('notice', "Couldn't find the venue");
            return $this->redirectToRoute('show_venues');
        }

        return $this->render('venue/venue_detail.html.twig', array('venue' => $venue));
    }

}