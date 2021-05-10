<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Entity\Like;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Place;
use App\Entity\Comment;
use App\Form\PlaceType;
use App\Entity\RouteLike;
use App\Form\CommentType;
use App\Repository\CityRepository;
use App\Repository\LikeRepository;
use App\Repository\PlaceRepository;
use App\Repository\CommentRepository;
use App\Repository\RouteLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/place")
 */
class PlaceController extends AbstractController
{

    /**
     * @Route("/carnet-de-route", name="carnet_de_route", methods={"GET"})
     */
    public function index(PlaceRepository $placeRepository, cityRepository $city): Response
    {
        $villes = $city->CityByName();

        return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findAll(),
            'villes' => $villes
        ]);
    }

    /**
     * @Route("/new", name="place_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $place = new Place();

        $user = new User();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            //on récupere les images
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier où l'image dois se Uploader
                $image->move(
                    $this->getParameter('image_place'),
                    $fichier
                );

                $img = new Image();
                $img->setName($fichier);
                $place->addImage($img);
            }

            $em = $this->getDoctrine()->getManager();
            $place->setUser($this->getUser());
            $em->persist($place);
            $em->flush();

            return $this->redirectToRoute('carnet_de_route');
        }

        return $this->render('place/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="place_show", methods={"POST", "GET"})
     */
    public function show(Request $request, Place $place, UserInterface $user, CommentRepository $cr): Response
    {


        $comment = new Comment();


        //creation d'un formaulaire
        $form = $this->createForm(CommentType::class, $comment);
        //envoi la request
        $form->handleRequest($request);

        //recupération de la date avec CARBON
        $tomorrow = Carbon::now('Europe/Paris')->locale('fr_Fr');
        $date = $tomorrow->isoFormat('LLLL');

        //filtre les commentaires par ID
        $findComment = $cr->findComment($place);

        //submit form
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $comment->setDate($date);
            $comment->setPlace($place->getId());
            $comment->setUser($this->getUser());
            $em->persist($comment);

            $em->flush();

            //ajour d'un message flash
            $this->addFlash(
                'notice',
                'Votre commentaire à bien été ajouter'
            );

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }else{
            //ajour d'un message flash
            $this->addFlash(
                'erreur',
                "erreur lors de l'envoi de votre commentaire"
            );
        }


        return $this->render('place/show.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
            'findComment' => $findComment,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="place_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Place $place): Response
    {

        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('carnet_de_route');
        }

        return $this->render('place/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }


    /**
     *@Route("/{id}/like", name="place_like")
     */
    public function Routelike(Place $place, RouteLikeRepository $routeRepo): Response {

        $user = $this->getUser();

        if(!$user) return $this->json(['code' => 403,'message' => "Vous êtes pas autorisez"],200);

        if($place->isRouteUser($user)){
            $like = $routeRepo->findOneBy([
                'place' => $place,
                'user' => $user
                ]
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json(['code' => 200, 'message' => 'Lieu aimer bien supprimer'], 200);
        }

        $like = new RouteLike();

        $like->setPlace($place)
            ->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($like);
            $em->flush();

        return $this->json(['code' => 200, 'message' => 'lieu ajouter avec succes'], 200);
    }

    /**
    * @Route("/{id}/like/place", name="like_place")
    */
    public function like(Place $place, LikeRepository $likerepo) : Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json(['code' => 403,'message' => "Vous êtes pas autorisez"],200);

        if($place->isLike($user)){
            $like = $likerepo->findOneBy([
                'place' => $place,
                'user' => $user
                ]
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json(['code' => 200, 'message' => 'Lieu plus aimer', 'like' => $likerepo->count(['place' => $place])], 200);
        }

        $like = new Like();

        $like->setPlace($place)
            ->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($like);
            $em->flush();

        return $this->json(['code' => 200, 'message' => 'lieu aimer',
                            'like' => $likerepo->count(['place' => $place])
                        ], 200);

    }

}