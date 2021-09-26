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
use App\Repository\CommentRepository;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use Symfony\Component\Mime\Address;
use App\Repository\SujetResponseRepository;
use App\Repository\ForumCategorieRepository;
use App\Repository\PlaceRepository;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Proxies\__CG__\App\Entity\Comment;
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
    public function index(SujetRepository $sujetrepo, ForumCategorieRepository $forum, Request $request): Response
    {

        $categories = $forum->findAll();

        $data = new SearchData();

        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $sujets = $sujetrepo->findAllSujet($data);

        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
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

           return $this->redirectToRoute('forum_accueil');
        }


        return $this->render('forum/new.html.twig', [
            'text' => 'text',
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/sujet/categorie/{categorie}", name="sujet_categorie",  methods={"POST", "GET"} )
     */
    public function forumCategorie(Sujet $sujet,ForumCategorieRepository $forum, SujetRepository $sujetrepo){

        $sujets = $sujetrepo->findSujetByCategorie($sujet->getCategorie());
        $getcat = $sujets[0]->categorie->getCategorie();
        $categories = $forum->findAll();

        return $this->render('forum/categories.html.twig', [
            'sujets' => $sujets,
            'categories' => $categories
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
                ->from(new Address('contact@coreego.fr', 'COREEGO | Forum'))
                ->to($userpostemail)
                ->subject('Un utilisateur à répondu à votre post')
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

        return $this->render('forum/show.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
            'res' => $res
        ]);
    }

    /**
     * @Route("/commentaire/delete/{id}", name="delete_commentaire", methods={"GET", "POST"} )
     */
    public function deletecommentaire(SujetResponseRepository $sujetresponserepo, $id, Request $request){

        $commentaire = $sujetresponserepo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }

    /**
     * @Route("/delete/sujet/{id}", name="deletesujet", methods={"GET", "POST"} )
     */
    public function deletesujet(SujetRepository $sujetrepo, Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $sujet = $sujetrepo->find($id);

        if($sujet->getUser()->getId() != $this->getUser()->getID() ){
            return $this->redirectToRoute('home');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($sujet);
        $em->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }

}
