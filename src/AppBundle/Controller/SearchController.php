<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 11:50
 */

namespace AppBundle\Controller;


use AppBundle\Entity\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SearchController extends Controller
{
    /**
     * @Route("/search", name="searchName")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchEventsOnName(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var EventRepository $repo
         */
        $repo = $em->getRepository('AppBundle:Event');

        $events = $repo->findEventByName($request->query->get('search'));
        return $this->render('search/Search_Events.html.twig', array('events' => $events));
    }
}