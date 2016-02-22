<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 22/02/2016
 * Time: 11:50
 */

namespace AppBundle\Controller;


use AppBundle\FormType\SearchEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;



class SearchController extends Controller
{
    /**
     * @Route("/search", name="searchName")
     */
    public function searchEventsOnName(Request $request)
    {
        $search = new SearchEvents();
        $events = array();

        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findEventByName($request->query->get('Search'));
        return $this->render('search/Search_Events.html.twig', array('events' => $events));
    }
}