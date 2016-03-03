<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\RoleType;
use AppBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for admin role management.
 * @package AppBundle\Controller
 */
class RoleController  extends Controller
{
	/**
	 * Shows a list of all available roles.
	 * @Route("/admin/role", name="adminroleoverview")
	 * @return Response
	 */
	public function overview()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository("AppBundle:Role");

		$roles = $repo->findAll();

		return $this->render(
			'admin/role/overview.html.twig',
			array(
				"roles" => $roles
			));
	}

	/**
	 * Adds a new role.
	 * @Route("/admin/role/add", name="adminaddrole")
	 * @param Request $request
	 * @return Response
	 */
	public function add(Request $request)
	{
		$role = new Role();

		$form = $this->createForm(RoleType::class, $role);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$repo = $em->getRepository('AppBundle:Role');

			$result = $repo->findOneBy(array("name" => $role->getName()));

			if($result === null)
			{
				$em->persist($role);
				$em->flush();

				$this->addFlash('notice', "Added role " . $role->getName());

				return $this->redirectToRoute("adminroleoverview");
			}
			else
			{
				$this->addFlash('notice', 'A role with that name already exists');
			}
		}

		return $this->render(
			'admin/role/add.html.twig',
			array(
				"form" => $form->createView()
			));
	}

	/**
	 * Edits a role.
	 * @Route("/admin/role/edit/{id}", name="admineditrole")
	 * @param Request $request
	 * @param integer $id Role id.
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('AppBundle:Role');

		$role = $repo->find($id);

		if($role === null)
		{
			$this->addFlash('notice', 'No role with that id exists');

			return $this->redirectToRoute('adminroleoverview');
		}

		$form = $this->createForm(RoleType::class, $role);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($role);
			$em->flush();

			$this->addFlash('notice', "Saved role " . $role->getName());

			return $this->redirectToRoute('adminroleoverview');
		}

		return $this->render(
			'admin/role/edit.html.twig',
			array(
				"form" => $form->createView()
			));
	}

	/**
	 * Removes a role, if it is not a mandatory role.
	 * @Route("/admin/role/remove/{id}", name="adminremoverole")
	 * @param integer $id Role id.
	 * @return RedirectResponse
	 */
	public function remove($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('AppBundle:Role');

		$role = $repo->find($id);

		if($role !== null)
		{
			if(!$role->isMandatory())
			{
				$em->remove($role);
				$em->flush();

				$this->addFlash('notice', "Role " . $role->getName() . " removed");
			}
			else
				$this->addFlash('notice', "Role " . $role->getName() . " is mandatory and cannot be removed");
		}
		else
			$this->addFlash('notice', 'No role with that id exists');

		return $this->redirectToRoute('adminroleoverview');
	}
}