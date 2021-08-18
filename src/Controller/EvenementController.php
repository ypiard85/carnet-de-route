<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(EvenementRepository $evenement): Response
    {


        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenement->findAll()
        ]);
    }

    /**
     * @Route("/evenement/{id}", name="evenement_show", methods={"GET", "POST"} )
     */
    public function show(Evenement $evenement)
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement
        ]);
    }
}
