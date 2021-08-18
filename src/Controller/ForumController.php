<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use App\Form\SujetType;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Entity\SujetResponse;
use App\Entity\ForumCategorie;
use App\Form\SujetResponseType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\SujetResponseRepository;
use App\Repository\ForumCategorieRepository;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum_accueil")
     */
    public function index(SujetRepository $sujetrepo, ForumCategorieRepository $forum): Response
    {

        $categories = $forum->findAll();

        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/forum/sujets" , name="forum_sujet", methods={"GET", "POST"} )
     */
    public function sujets(SujetRepository $sujetrepo, Request $request) : Response
    {

        $data = new SearchData();

        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $sujets = $sujetrepo->findAllSujet($data);

        return $this->render('forum/sujetfilter.html.twig', [
            'sujets' => $sujets,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sujet/new", name="sujet_new")
     */
    public function new(Request $request) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sujet = new Sujet();


        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $sujet->setCreatedAt(new DateTime());
            $sujet->setUser($this->getUser());
            $em->persist($sujet);
            $em->flush();



             //ajour d'un message flash
             $this->addFlash(
                'notice',
                'Sujet ajouter avec succes'
            );

           return $this->redirect($_SERVER['HTTP_REFERER']);
        }


        return $this->render('forum/new.html.twig', [
            'text' => 'text',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sujet/categorie/{categorie}", name="sujet_categorie",  methods={"POST", "GET"} )
     */
    public function forumCategorie(Sujet $sujet, SujetRepository $sujetrepo){

        $sujets = $sujetrepo->findSujetByCategorie($sujet->getCategorie());
        $getcat = $sujets[0]->categorie->getCategorie();


        return $this->render('forum/categories.html.twig', [
            'sujets' => $sujets,
            'cats' => $getcat,
        ]);
    }

    /**
     * @Route("/sujet/{id}/{title}", name="sujet_view", methods={"POST", "GET"} )
     */
    public function sujet( SujetResponseRepository $responserepo, Sujet $sujet, Request $request, UserRepository $userrepo, MailerInterface $mailer) : Response
    {


        $sujetresponse = new SujetResponse();


        $res = $responserepo->sujetComment($sujet->getId());

        $form = $this->createForm(SujetResponseType::class, $sujetresponse );
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $userpost = $userrepo->find($sujet->getUser()->getId());
            $userpostemail = $userpost->getEmail();

            if($this->getUser()->getId() != $sujet->getUser()->getId())
            {
                //send email
                $email = (new TemplatedEmail())
                ->from('contact@lacoree.fr')
                ->to($userpostemail)
                ->subject('Time for Symfony Mailer!')
                ->htmlTemplate('email/email_forum_response.html.twig')
                ->context([
                    'id' => $sujet->getId(),
                    'title' => $sujet->getTitle(),
                ]);
                $mailer->send($email);
            }

            $em = $this->getDoctrine()->getManager();
            $sujetresponse->setUser($this->getUser());
            $sujetresponse->setSujet($sujet);
            $sujetresponse->setCreatedAt(new DateTime());
            $em->persist($sujetresponse);
            $em->flush();

                //ajour d'un message flash
                $this->addFlash(
                    'notice',
                    'Votre commentaire à bien été ajouter'
                );

                $referer = $request->headers->get('referer');
                return $this->redirect($referer);

        }

        return $this->render('forum/sujet.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
            'res' => $res
        ]);
    }
}
