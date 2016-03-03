<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 11:50
 */

namespace AppBundle\Controller;


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
        $events = $em->createQuery("Select e from AppBundle:Event as e where lower(e.name) LIKE lower(:search) or lower(e.city) LIKE lower(:search) or lower(e.adress) LIKE lower(:search)")->setParameter('search', '%' . $request->query->get('search') . '%')->getResult();

        return $this->render('search/Search_Events.html.twig', array('events' => $events, 'search'=>$request->query->get('search')));
    }
}