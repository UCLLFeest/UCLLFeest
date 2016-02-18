<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\UserType;
use AppBundle\Form\EditUserType;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Entity\User;
use AppBundle\Entity\LoginData;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountController extends Controller
{
    /**
     * @Route("/account/register", name="register")
     */
    public function register(Request $request)
    {
        //Disallow registration if logged in
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->addFlash('notice', 'You are already registered.');

            return $this->redirectToRoute('homepage');
        }

        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            $this->addFlash('notice', 'Your registration is now complete!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'account/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/account/login", name="login")
     */
    public function login(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createFormBuilder(new LoginData($lastUsername))
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->getForm();

        return $this->render(
            'account/login.html.twig',
            array(
                'form' => $form->createView(),
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/account/view/{id}", name="accountview")
     */
    public function view(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
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
     * @Route("/account/viewall", name="viewallusers")
     */
    public function viewall()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');
        $users = $repo->findAll();

        return $this->render(
            'account/viewall.html.twig',
            array(
                'users' => $users
            ));
    }

    /**
     * @Route("/account/editpassword", name="editpassword")
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