<?php

/**
 * Class DefaultController
 */

namespace AppBundle\Controller;

use AppBundle\Entity\EventRepository;
use Geocoder\Provider\GeoPlugin;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the homepage.
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
	 * Displays the homepage.
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$ip =  "193.190.138.251";
        $ip = $request->getClientIp();
        $curl     = new CurlHttpAdapter();
        $geocoder = new GeoPlugin($curl);
        $adress =  $geocoder->geocode($ip);

        /**
         * @var EventRepository $repo
         */
        $repo = $em->getRepository('AppBundle:Event');

        //check of er events in de buurt zijn
        $events = $repo->sortEventByLocationDistance($adress->get(0)->getLatitude(),$adress->get(0)->getLongitude());


        //zo niet geef aflopende datum events
        if(empty($events)) {
            //CURRENT_TIMESTAMP()
            $events = $em->createQuery('Select e from AppBundle:Event as e where e.date > CURRENT_TIMESTAMP()')
                ->setMaxResults(20)
                ->getResult();
        }

        //WANNEER ER GEEN EVENTS ZIJN (mag niet gebeuren, gewoon events in het verleden tonen)
        if (empty($events)) {
            $events = $em->createQuery('Select e from AppBundle:Event as e')
                ->setMaxResults(20)
                ->getResult();
        }



        return $this->render(
            'default/index.html.twig',array('events'=>$events)
        );
    }
}
