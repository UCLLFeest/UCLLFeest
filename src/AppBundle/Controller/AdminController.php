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

        if($user ===  null)
		{
			$this->addFlash('notice', "The user with id $id does not exist");

			return $this->redirectToRoute("/admin/overview");
		}

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

        if($user ===  null)
		{
			$this->addFlash('notice', "The user with id $id does not exist");

			return $this->redirectToRoute("/admin/overview");
		}

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
			$this->addFlash('notice', "Role $role cannot be removed");

		return $this->redirectToRoute("adminview", array("id" => $id));
    }

	/**
	 * @Route("/admin/changerole/{id}/{role}", name="adminchangerole")
	 * @param Request $request
	 * @param integer $id
	 * @param string $role
	 */
	public function changeRole(Request $request, $id, $role)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:User');

		$user = $repo->find($id);

		if($user ===  null)
		{
			$this->addFlash('notice', "The user with id $id does not exist");

			return $this->redirectToRoute("/admin/overview");
		}

		if($role === User::ROLE_DEFAULT)
		{
			$this->addFlash('notice', "Role $role cannot be changed");

			return $this->redirectToRoute("adminview", array("id" => $id));
		}
		else if (!$user->hasRole($role))
		{
			$this->addFlash('notice', "The user does not have role $role");

			return $this->redirectToRoute("adminview", array("id" => $id));
		}

		$changeRole = array(
			'role' => $role
		);

		$form = $this->createFormBuilder($changeRole)
			->add('role', TextType::class)
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$changeRole = $form->getData();

			$newRole = $changeRole['role'];

			if ($newRole !== User::ROLE_DEFAULT)
			{
				if (!$user->hasRole($newRole))
				{
					$this->addFlash('notice', "Changed $role to $newRole");
					$user->removeRole($role);
					$user->addRole($newRole);

					$em->persist($user);
					$em->flush();

					return $this->redirectToRoute("adminview", array("id" => $id));
				}
				else
					$this->addFlash('notice', "The user already has role $newRole");
			}
			else
				$this->addFlash('notice', "Cannot change $role to $newRole");
		}

		return $this->render("admin/changerole.html.twig",
			array(
				"form" => $form->createView(),
				"role" => $role
			));
	}
}