<?php

namespace App\Controller;
use DateTime;
use App\Data\SearchData;
use App\Entity\ActuImage;
use App\Entity\Actualites;
use App\Form\ActualitesType;
use App\Repository\ActualitesRepository;
use App\Repository\ActuImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/actualites")
 */
class ActualitesController extends AbstractController
{
    /**
     * @Route("/index", name="actualites")
     */
    public function index(ActualitesRepository $acturepo, Request $request): Response
    {

        $data = new SearchData();

        $actualites = $acturepo->finadAllActualites();

        $data->page = $request->get('page', 1);

        return $this->render('actualites/index.html.twig', [
           'actualites' => $actualites
        ]);
    }

    /**
     * @Route("/new", name="actualites_new", methods={"GET","POST"} )
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');



        $actualites = new Actualites();

        $form = $this->createForm(ActualitesType::class, $actualites);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $image = $form->get('name')->getData();

            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                $this->getParameter('actualite'),
                $fichier
            );

            $img = new ActuImage();
            $img->setName($fichier);
            $actualites->addActuImage($img);


            $em = $this->getDoctrine()->getManager();
            $actualites->setCreatedAt(new DateTime());

            $em->persist($actualites);
            $em->persist($img);
            $em->flush();



            return $this->redirectToRoute('actualites');

        };


        return $this->render('actualites/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/edit/{id}", name="actualites_edit", methods={"GET","POST"} )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Actualites $actualites, ActuImageRepository $actuimgrepo)
    {

        $form = $this->createForm(ActualitesType::class, $actualites);

        $form->handleRequest($request);

        $getrepo = $actuimgrepo->findOneBy(['actualite' => $actualites ]);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('messages', 'Actualite modifier avec success' );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actualites');

        }

        return $this->render('actualites/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/{title}", name="actualites_show", methods={"GET","POST"} )
     */
    public function show(Actualites $actualites): Response
    {

        return $this->render('actualites/show.html.twig', [
            'actualites' => $actualites
        ]);
    }

      /**
     * @Route("/delete/{id}", name="actualites_delete", methods={"GET","POST"} )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Actualites $actualites): Response
    {

        $this->addFlash('messages', 'Actualite supprimer' );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($actualites);
        $entityManager->flush();


        return $this->redirectToRoute('actualites');
    }
}
