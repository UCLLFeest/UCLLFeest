<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 19/02/2016
 * Time: 12:57
 */

namespace AppBundle\Controller;

use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

//https://github.com/Payum/PayumBundle/blob/master/Resources/doc/custom_purchase_examples/paypal_express_checkout.md
//http://stackoverflow.com/questions/28148887/setting-up-payum-bundle-with-symfony2-giving-error
//https://github.com/Payum/Payum/blob/master/src/Payum/Core/Resources/docs/examples/index.md

class PaymentController extends Controller
{

    /**
     * @Route("/order", name="buy_ticket")
     */

    public function prepareAction()
    {
        $gatewayName = 'paypal';
        $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount(123); // 1.23 EUR
        $payment->setDescription('A description');
        $payment->setClientId('5');
        $payment->setClientEmail('driescroonspp-facilitator@gmail.com');

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'done' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    /**
     * @Route("/done", name="done")
     */

    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum')->getHttpRequestVerifier()->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$payment = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'payment' => array(
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ),
        ));
    }
}