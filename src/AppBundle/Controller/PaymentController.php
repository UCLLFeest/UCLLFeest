<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 19/02/2016
 * Time: 12:57
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Payment;
use AppBundle\Entity\TicketRepository;
use Payum\Core\Gateway;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Security\TokenInterface;
use Payum\Core\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Ticket;

//https://github.com/Payum/PayumBundle/blob/master/Resources/doc/custom_purchase_examples/paypal_express_checkout.md
//http://stackoverflow.com/questions/28148887/setting-up-payum-bundle-with-symfony2-giving-error
//https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/examples/index.md

class PaymentController extends Controller
{

    /**
     * @Route("/order/{id}", name="buy_ticket")
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function prepareAction($id)
    {

        $em = $this->getDoctrine()->getManager();

		/**
		 * @var Event $event
		 */
        $event = $em->getRepository('AppBundle:Event')->find($id);

        if($event->getSelling() && $event->getTickets()->count() < $event->getCapacity()) {
            $gatewayName = 'paypal';
            /**
             * @var StorageInterface $storage
             */
            $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

			/**
			 * @var Payment $payment
			 */
            $payment = $storage->create();
            $payment->setNumber(uniqid());
            $payment->setCurrencyCode('EUR');
            $payment->setTotalAmount($event->getPrice() * 100); // 1.23 EUR
            $payment->setDescription($event->getName());
            $payment->setClientId($this->getUser()->getId());
            $payment->setClientEmail($this->getUser()->getEmail());

            $payment->setDetails(array(
                'custom' => $id
            ));

            $storage->update($payment);

            /**
             * @var TokenInterface $captureToken
             */
            $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
                $gatewayName,
                $payment,
                'done' // the route to redirect after capture
            );

            return $this->redirect($captureToken->getTargetUrl());
        }

        $this->addFlash('notice', 'This event is not selling tickets!');
        return $this->redirectToRoute('show_tickets');
    }

    /**
     * @Route("/done", name="done")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function doneAction(Request $request)
    {
		/**
		 * @var TokenInterface $token
		 */
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

		/**
		 * @var Gateway $gateway
		 */
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$payment = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        //var_dump($status = new GetHumanStatus($token));
        $gateway->execute($status = new GetHumanStatus($token));
        //voor details te tonen (is al opgeslagen)

        $payment = $status->getFirstModel();


        //https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/examples/4-get-status.md statusses


        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.
        /*return new JsonResponse(array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
                'clientid' => $payment->getClientID(),
                'clientemail' => $payment->getClientEmail(),
                'EVENTID' => $payment->getDetails()['DESC'],
            ),
        ));*/

        if ($status->isPending() ||$status->isAuthorized()) {
            $ticket = new Ticket();

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $user->addTicket($ticket);
            $ticket->setOwner($user);


			/**
			 * @var Event $event
			 */
            $event = $em->getRepository('AppBundle:Event')->find($payment->getDetails()['custom']);

            $ticket->setEvent($event);

			/**
			 * @var TicketRepository $repo
			 */
			$repo = $em->getRepository('AppBundle:Ticket');

            if ($repo->findIfPersonHasTicket($event->getId(), $user->getId()) == null) {
                //moet user ook niet gepersist worden?
                $em->persist($ticket);
                $em->flush();

                $em->persist($user);
                $em->flush();

                //return $this->showTickets();
            }


            return $this->redirectToRoute('show_tickets');
        }

        $this->addFlash('notice', "Woops, something went wrong");
        return $this->redirectToRoute('show_tickets');
    }
}