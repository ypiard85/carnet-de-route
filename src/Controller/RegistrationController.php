<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $email = $form['email']->getData();

            $user->setRoles(['ROLE_USER']);
            $user->setToken(md5(uniqid()));


            //on génère le token d'activation
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('yoann.piard@gmail.com', 'COREEGO'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre email')
                    ->context(['token' => $user->getActivationToken()])
                    ->htmlTemplate('email/email_registration.html.twig')
            );
            // do anything else you need here, like send an email

            /*return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );*/

            return $this->redirectToRoute('email_send');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
           $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('email_valid', 'Your email address has been verified.');

        return $this->redirectToRoute('activation');
    }

    /**
     * @Route("/emailsend", name="email_send" )
     */
    public function emailsend(){
        return $this->render('registration/emailsend.html.twig');
    }

    /**
     * @Route("/activation/{token}", name="activation" )
     */
    public function activation($token, UserRepository $userrepo)
    {

        $user = $userrepo->findOneBy(['activation_token' => $token]);

        //si aucun utilisateur n'existe avec ce token
        if(!$user)
        {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas ");
        }

            //on supprime le token
            $user->setActivationToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();


        $this->addFlash('message', 'Votre compte est activé');

        return $this->redirectToRoute('app_login');

    }
}
