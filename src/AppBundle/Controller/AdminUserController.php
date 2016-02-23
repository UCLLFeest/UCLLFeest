<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminUserController extends Controller
{
    /**
     * @Route("/admin/user", name="adminuseroverview")
     */
    public function overview()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $users = $repo->findAll();

        return $this->render(
            'admin/user/overview.html.twig',
            array('users' => $users)
        );
    }

	/**
	 * @Route("/admin/user/view/{id}", name="adminuserview")
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function view($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user !==  null)
        {
            return $this->render("admin/user/view.html.twig",
                array(
                    "user" => $user
            ));
        }
        else
        {
            $this->addFlash('notice', "The user with id $id does not exist");

            return $this->redirectToRoute("/admin/user");
        }
    }

	/**
	 * @Route("/admin/user/addrole/{id}", name="adminuseraddrole")
	 * @param Request $request
	 * @param integer $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function addrole(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user ===  null)
		{
			$this->addFlash('notice', "The user with id $id does not exist");

			return $this->redirectToRoute("/admin/user");
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

			return $this->redirectToRoute("adminuserview", array("id" => $id));
		}

		return $this->render('admin/user/addrole.html.twig',
			array(
				'form' => $form->createView()
		));
    }

	/**
	 * @Route("/admin/user/removerole/{id}/{role}", name="adminuserremoverole")
	 * @param integer $id
	 * @param string $role
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function removerole($id, $role)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user ===  null)
		{
			$this->addFlash('notice', "The user with id $id does not exist");

			return $this->redirectToRoute("/admin/user");
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

		return $this->redirectToRoute("adminuserview", array("id" => $id));
    }

	/**
	 * @Route("/admin/user/changerole/{id}/{role}", name="adminchangerole")
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

			return $this->redirectToRoute("/admin/user");
		}

		if($role === User::ROLE_DEFAULT)
		{
			$this->addFlash('notice', "Role $role cannot be changed");

			return $this->redirectToRoute("adminUserview", array("id" => $id));
		}
		else if (!$user->hasRole($role))
		{
			$this->addFlash('notice', "The user does not have role $role");

			return $this->redirectToRoute("adminUserview", array("id" => $id));
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

					return $this->redirectToRoute("adminuserview", array("id" => $id));
				}
				else
					$this->addFlash('notice', "The user already has role $newRole");
			}
			else
				$this->addFlash('notice', "Cannot change $role to $newRole");
		}

		return $this->render("admin/user/changerole.html.twig",
			array(
				"form" => $form->createView(),
				"role" => $role
			));
	}
}