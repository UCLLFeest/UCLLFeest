<?php

namespace AppBundle\Controller;

use Geocoder\Provider\GeoPlugin;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
            $em = $this->getDoctrine()->getManager();
          //  $ip =  "193.190.138.250";
             $ip = $request->getClientIp();
            $curl     = new CurlHttpAdapter();
            $geocoder = new GeoPlugin($curl);
            $adress =  $geocoder->geocode($ip);

            //ik geef hem hier efkes 50 aan om te testen
            $events = $em->getRepository('AppBundle:Event')->sortEventByLocationDistance($adress->get(0)->getLatitude(),$adress->get(0)->getLongitude());


        return $this->render(
            'default/index.html.twig',array('events'=>$events)
        );
    }
}
