<?php

namespace App\Controller;

use Stripe\Stripe;
use Knp\Snappy\Pdf;
use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Bundle\TimeBundle\KnpTimeBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Api\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/stripe")
 */
class StripeController extends AbstractController
{
    /**
     * @Route("/basic/create-checkout-session", name="stripe_basic", methods={"GET", "POST"} )
     *
     */
    public function basic(Request $request)
    {
        //https://stripe.com/docs/billing/subscriptions/checkout

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        \Stripe\Stripe::setApiKey('sk_test_51E5Eq1AbBq4Fu4eUVvkqA3kOkfiON7FRpTdRfpblsB2LZ1nZehTjnAYdiAhWvDYxTJPuVsF7ytBu8bL2Dn77O0PR00PRLZSTrW');

        // The price ID passed from the front end.
        //   $priceId = $_POST['priceId'];

        $priceId = $request->request->get('priceId');

        $session = \Stripe\Checkout\Session::create([
          'success_url' => $this->generateUrl('success', [] , UrlGeneratorInterface::ABS_URL ),
          'cancel_url' => $this->generateUrl('erreur', [], UrlGeneratorInterface::ABS_URL ),
          'payment_method_types' => ['card'],
          'mode' => 'subscription',
          'line_items' => [
            [
            'price' => $priceId,
            'quantity' => 1,
          ]],
        ]);

        return $this->redirect('' . $session->url);
    }

    /**
     * @Route("/entreprise/create-checkout-session", name="stripe_entreprise", methods={"POST", "GET"} )
     *
     */
    public function entreprise(Request $request)
    {
        //https://stripe.com/docs/billing/subscriptions/checkout

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        \Stripe\Stripe::setApiKey('sk_test_51E5Eq1AbBq4Fu4eUVvkqA3kOkfiON7FRpTdRfpblsB2LZ1nZehTjnAYdiAhWvDYxTJPuVsF7ytBu8bL2Dn77O0PR00PRLZSTrW');

        // The price ID passed from the front end.
        //   $priceId = $_POST['priceId'];

        $priceId = $request->request->get('priceId');

        $session = \Stripe\Checkout\Session::create([
          'success_url' => $this->generateUrl('success_entreprise', [], UrlGeneratorInterface::ABS_URL ),
          'cancel_url' => $this->generateUrl('erreur', [], UrlGeneratorInterface::ABS_URL ),
          'payment_method_types' => ['card'],
          'mode' => 'subscription',
          'line_items' => [[
            'price' => $priceId,
            // For metered billing, do not pass quantity
            'quantity' => 1,
          ]],
        ]);

        return $this->redirect('' . $session->url);
    }

    /**
     * @Route("/success/115871fezdi584778", name="success",  methods={"POST", "GET"} )
     */
    public function success( Request $request ){

      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');



            $this->getUser()->setRoles(['ROLE_PREMIUM']);


            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('sucessbasic_html', ['token' => $this->getUSer()->getToken()]);
    }

    /**
     * @Route("/erreur", name="erreur")
     */
    public function erreur(){
        return $this->render('stripe/erreur.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    /**
     * @Route("/successentreprise/115871574768apoev877iez", name="success_entreprise", methods={"POST", "GET"})
     */
    public function successentreprise(UserRepository $userrepo, User $user){

      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

      /*if($this->getUser()->getToken() != $user->getToken()){
        return $this->redirectToRoute('home');
      }*/

        $this->getUser()->setRoles(['ROLE_ENTREPRISE']);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('sucessentreprise_html', ['token' => $this->getUSer()->getToken()]);
    }

    /**
     * @Route("/success/basic/{token}", name="sucessbasic_html", methods={"GET", "POST"} ): Response
     */
    public function successbasichtml()
    {

      return $this->render('stripe/success.html.twig');

    }

    /**
     * @Route("/success/entreprise/{token}", name="sucessentreprise_html", methods={"GET", "POST"}  )
     */
    public function successentreprisehtml(User $user): Response
    {

      return $this->render('stripe/successentreprise.html.twig', [
        'user' => $user
      ]);
    }
}
