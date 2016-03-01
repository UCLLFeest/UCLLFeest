<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\EditUserType;
use AppBundle\Form\ChangePasswordType;
use AppBundle\FormType\ChangePassword;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/account/view/{id}", name="accountview")
     * @param integer $id Target user id
     * @return Response|RedirectResponse
     */
    public function view($id)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var UserRepository $repo
         */
        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($id);

        if($user === null)
        {
            $this->addFlash('notice', 'That user does not exist');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'account/view.html.twig',
            array('user' => $user)
        );
    }

    /**
     * @Route("/account/all", name="viewallusers")
     */
    public function viewall()
    {
        $em = $this->getDoctrine()->getManager();

		/**
		 * @var UserRepository $repo
		 */
        $repo = $em->getRepository('AppBundle:User');
        $users = $repo->findAll();

        return $this->render(
            'account/viewall.html.twig',
            array(
                'users' => $users
            ));
    }

    /**
     * @Route("/account/profile", name="profile")
     */
    public function profile()
    {
        $user = $this->getUser();

        if($user === null)
        {
            $this->addFlash('notice', 'That user does not exist');
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'account/view.html.twig',
            array('user' => $user)
        );
    }


    /**
     * @Route("/account/editpassword", name="editpassword")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editPassword(Request $request)
    {
        $user = $this->getUser();

        $changePassword = new ChangePassword();

        // 1) build the form
        $form = $this->createForm(ChangePasswordType::class, $changePassword);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                             ->encodePassword($user, $changePassword->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            $this->addFlash('notice', 'Your password has been changed.');

            return $this->redirectToRoute('accountview', array('id' => $user->getId()));
        }

        return $this->render(
            'account/editpassword.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/account/editprofile", name="editprofile")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function editprofile(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            $this->addFlash('notice', 'Changes saved.');

            return $this->redirectToRoute('accountview', array('id' => $user->getId()));
        }

        return $this->render(
            'account/editprofile.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }
}