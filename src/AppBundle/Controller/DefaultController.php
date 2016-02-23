<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need


        $events =  array();
            $em = $this->getDoctrine()->getManager();
        //TODO
        //Hardcoded moet nog verandert worden
            $ip =  "193.190.138.250";
            $curl     = new \Ivory\HttpAdapter\CurlHttpAdapter();
            $geocoder = new \Geocoder\Provider\GeoPlugin($curl);
            $adress =  $geocoder->geocode($ip);

            $events = $em->getRepository('AppBundle:Event')->sortEventByLocationDistance($adress->get(0)->getLatitude(),$adress->get(0)->getLongitude() );


        return $this->render(
            'default/index.html.twig',array('events'=>$events)
        );

    }


}
