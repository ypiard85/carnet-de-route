<?php

namespace App\Controller;

use DateTime;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Form\MessageEvenementType;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/evenement")
*/
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement")
     */
    public function index(EvenementRepository $evenement): Response
    {

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenement->allEvenement()
        ]);
        
    }

    /**
     * @Route("/ajouter", name="evenement_add", methods={"GET", "POST"} )
     */
    public function new(Request $request, MailerInterface $mailer, UserRepository $user): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $evenement = new Evenement();

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        $premiums = $user->findUserPremium();

        if($form->isSubmitted() && $form->isValid())
        {



            $em = $this->getDoctrine()->getManager();
            $evenement->setUser($this->getUser());
            $evenement->setCreatedAt(new DateTime());
            $em->persist($evenement);
            $em->flush();

            forEach($premiums as $premium){

                //send email
                $email = (new TemplatedEmail())
                ->from($this->getUser()->getEmail())
                ->to($premium->getEmail())
                ->subject($evenement->getTitre())
                ->htmlTemplate('email/email_evenement_publish.html.twig')
                ->context([
                    'pseudo' => $this->getUser()->getPseudo(),
                    'titre' => $form['titre']->getData(),
                    'evenement' => $evenement->getId()
                ]);
                $mailer->send($email);
            }

            return $this->redirectToRoute('evenement');
        }

        return $this->render('evenement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

      /**
     * @Route("/edit/{id}", name="evenement_edit", methods={"GET", "POST"} )
     */
    public function edit(Request $request, Evenement $evenement): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('evenement');
        }

        return $this->render('evenement/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET", "POST"} )
     */
    public function show(Evenement $evenement, Request $request, MessageEvenementType $messageevent, MailerInterface $mailer)
    {
        $form = $this->createForm(MessageEvenementType::class);
        $form->handleRequest($request);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if($form->isSubmitted() && $form->isValid())
        {

            $userpostemail = $this->getUser()->getEmail();

            $this->addFlash('message', 'Votre email à bien été envoyé' );

            //send email
            $email = (new TemplatedEmail())
            ->from($this->getUser()->getEmail())
            ->to($evenement->getUser()->getEmail())
            ->subject($evenement->getTitre())
            ->htmlTemplate('email/email_evenement.html.twig')
            ->context([
                'pseudo' => $this->getUser()->getPseudo(),
                'emailuser' => $userpostemail,
                'titre' => $evenement->getTitre(),
                'content' => $form['messageevent']->getData()

            ]);
            $mailer->send($email);
        }

        return $this->render('evenement/show.html.twig',  [
            'evenement' => $evenement,
            'form' => $form->createView()
        ]);
    }

}
