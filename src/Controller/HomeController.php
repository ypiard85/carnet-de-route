<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Form\ContactType;
use App\Repository\CityRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaceRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{



    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, cityRepository $city, CategorieRepository $catrepo, PlaceRepository $placerepo): Response
    {


        $villes = $city->CityByName();
        $categories = $catrepo->categoriebynom();

        $lieuxinsolites = $placerepo->findBy(['categorie' => 6], ['id' => 'DESC'], 10);
        $premiums = $placerepo->findBy(['premium' => 1], ['id' => 'DESC'], 10 );

        $likes = $placerepo->placeMoreLikes();

        //dd($likes);

        return $this->render('home/index.html.twig', [
            'villes' => $villes,
            'categories' => $categories,
            'lieuxinsolites' => $lieuxinsolites,
            'premiums' => $premiums
        ]);
    }

    /**
     * @Route("/contact", name="contact", methods={"POST", "GET"} )
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $prenom = $form['prenom']->getData();
        $nom = $form['nom']->getData();
        $emailuser = $form['email']->getData();
        $objet = $form['objet']->getData();
        $message = $form['message']->getData();

        if($form->isSubmitted() && $form->isValid()){
             //send email
             $email = (new TemplatedEmail())
             ->from($emailuser)
             ->to('contact.coreego@gmail.com')
             ->subject($objet)
             ->htmlTemplate('email/email_contact.html.twig')
             ->context([
                'objet' => $objet,
                'message' => $message,
                'prenom' => $prenom,
                'nom' => $nom,
                'emailuser' => $emailuser
             ]);

             $mailer->send($email);

             $this->addFlash('message', 'Votre message à bien été envoyé');

             return $this->redirectToRoute('contact');
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fonctionnement", name="fonctionnement")
     */
    public function fonctionnement()
    {
        return $this->render('home/fonctionnement.html.twig', [
            'test' => 'test'
        ]);
    }
}
