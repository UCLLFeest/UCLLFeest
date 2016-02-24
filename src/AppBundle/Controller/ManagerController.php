<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 22/02/2016
 * Time: 13:31
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller
{
    /**
     * @Route("/managers/{id}", name="show_managers")
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showManagersForEvent($id)
    {
        //checken of user die checked of dit de creator is

        //Alle evenementen worden opgezogt en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if($user != $event->getCreator()) {
            //////
            $this->addFlash('notice', "You are not allowed to access this page");
            return $this->redirectToRoute('homepage');
            ///////
        }

        return $this->render('manager/detail_manager.html.twig',array('event'=>$event));
    }

    /**
     * @Route("/managers/add/{event_id}", name="add_manager")
     * @param Request $request
     * @param integer $event_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addManager(Request $request, $event_id)
    {
        //Alle evenementen worden opgezogt en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();
        /**
         * @var Event $event
         */
        $event = $em->getRepository('AppBundle:Event')->find($event_id);

        if($user != $event->getCreator()) {
            //////
            $this->addFlash('notice', "You are not allowed to access this page");
            return $this->redirectToRoute('homepage');
            ///////
        }

		/**
		 * @var UserRepository $repo
		 */
		$repo = $em->getRepository('AppBundle:User');

        /**
         * @var User $manager
         */
		/** @noinspection PhpUndefinedMethodInspection */
		$manager = $repo->findOneByUsername($request->query->get('username'));

        if ($manager != null) {
            if(!$event->getManagers()->contains($manager)) {
                $event->addManager($manager);
                $manager->addManaging($event);


                $em->persist($manager);
                $em->persist($event);

                $em->flush();

                $this->addFlash('notice', "Inserted");
                return $this->redirectToRoute('show_managers', array('id'=>$event->getId()));
            } else {
                $this->addFlash('notice', "User is already a manager");
                return $this->redirectToRoute('show_managers', array('id'=>$event->getId()));
            }
        }

        return $this->render('manager/add_manager.html.twig',array('event'=>$event));

    }

    /**
     * @Route("/managers/delete/{event_id}/{username}", name="delete_manager")
     * @param integer $event_id
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteManager($event_id, $username)
    {
        //Alle evenementen worden opgezogt en in een array doorgegeven naar de view
        $em =$this->getDoctrine()->getManager();
        $user = $this->getUser();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($event_id);


        if($user != $event->getCreator()) {
            //////
            $this->addFlash('notice', "You are not allowed to access this page");
            return $this->redirectToRoute('homepage');
            ///////
        }

		/**
		 * @var UserRepository $repo
		 */
		$repo = $em->getRepository('AppBundle:User');

		/**
		 * @var User $manager
		 */
		/** @noinspection PhpUndefinedMethodInspection */
		$manager = $repo->findOneByUsername($username);

        if ($manager != null) {
            if($event->getManagers()->contains($manager)) {


                $event->removeManager($manager);
                $manager->removeManaging($event);

                $em->persist($manager);
                $em->persist($event);

                $em->flush();

                $this->addFlash('notice', "Removed");
                return $this->redirectToRoute('show_managers', array('id'=>$event->getId()));

            } else {
                $this->addFlash('notice', "User is not a manager");
                return $this->redirectToRoute('show_managers', array('id'=>$event->getId()));
            }
        }

        return $this->render('manager/add_manager.html.twig',array('event'=>$event));

    }

}
