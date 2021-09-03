<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Form\ContactType;
use App\Repository\CityRepository;
use App\Repository\CategorieRepository;
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
    public function index(Request $request, cityRepository $city, CategorieRepository $catrepo): Response
    {

        $tomorrow = Carbon::now('Asia/Seoul');
        $h = $tomorrow->isoFormat('h');
        $m = $tomorrow->isoFormat('mm');

        if($request){
            $filter = [
                $request->get('q'),
                $request->get('city'),
                $request->get('aime'),
                $request->get('categorie'),
            ];
        }

        $villes = $city->CityByName();
        $categories = $catrepo->categoriebynom();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'h' => $h, 'm' => $m,
            'filter' => $filter,
            'villes' => $villes,
            'categories' => $categories
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
             ->to('yoann.piard@gmail.com')
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
}
