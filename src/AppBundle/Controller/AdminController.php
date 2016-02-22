<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminController extends Controller
{
    /**
     * @Route("/admin/overview", name="adminoverview")
     */
    public function overview()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $users = $repo->findAll();

        return $this->render(
            'admin/overview.html.twig',
            array('users' => $users)
        );
    }

    /**
     * @Route("/admin/view/{id}", name="adminview")
     * @param integer $id
     */
    public function view($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user !==  null)
        {
            return $this->render("admin/view.html.twig",
                array(
                    "user" => $user
            ));
        }
        else
        {
            $this->addFlash('notice', "The user with id $id does not exist");

            return $this->redirectToRoute("/admin/overview");
        }
    }

    /**
     * @Route("/admin/addrole/{id}", name="adminaddrole")
	 * @param Request $request
     * @param integer $id
     */
    public function addRole(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user !==  null)
        {
			$addRole = array();

			$form = $this->createFormBuilder($addRole)
				->add('role', TextType::class)
				->getForm();

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid())
			{
				$addRole = $form->getData();

				$role = $addRole['role'];

				if (!$user->hasRole($role))
				{
					$this->addFlash('notice', "Role $role added");
					$user->addRole($role);

					$em->persist($user);
					$em->flush();
				}
				else
					$this->addFlash('notice', "The user already has the role $role");

				return $this->redirectToRoute("adminview", array("id" => $id));
			}

			return $this->render('admin/addrole.html.twig',
				array(
					'form' => $form->createView()
			));
        }
        else
        {
            $this->addFlash('notice', "The user with id $id does not exist");

            return $this->redirectToRoute("/admin/overview");
        }
    }

    /**
     * @Route("/admin/removerole/{id}/{role}", name="adminremoverole")
     * @param integer $id
     * @param string $role
     */
    public function removeRole($id, $role)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user !==  null)
        {
            if($role !== User::ROLE_DEFAULT)
            {
                if($user->hasRole($role))
                {
                    $this->addFlash('notice', "Role $role removed");
                    $user->removeRole($role);

                    $em->persist($user);
                    $em->flush();
                }
                else
                    $this->addFlash('notice', "The user has no role $role");
            }
            else
                $this->addFlash('notice', User::ROLE_DEFAULT . "cannot be removed");

            return $this->redirectToRoute("adminview", array("id" => $id));
        }
        else
        {
            $this->addFlash('notice', "The user with id $id does not exist");

            return $this->redirectToRoute("/admin/overview");
        }
    }
}