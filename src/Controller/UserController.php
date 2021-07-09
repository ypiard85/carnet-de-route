<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Place;
use App\Form\UserType;
use App\Entity\RouteLike;
use App\Repository\UserRepository;
use App\Repository\PlaceRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\RouteLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, PlaceRepository $placerepo, LikeRepository $likerepo, Request $request, $id ): Response
    {

        $places = $placerepo->findBy(['user' => $id]);



        $likes = $likerepo->findLikeByUser($id);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'places' => $places,
            'likes' => $likes,
        ]);
    }

    /**
     * @Route("/{pseudo}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userrepo): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

                //ajour d'un message flash
                $this->addFlash(
                    'notice',
                    'Profil mis a jour'
                );

                $this->getDoctrine()->getManager()->flush();


                return $this->redirectToRoute('user_show', [ 'id' => $user->getId()] );
            }



            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/carnet/{id}", methods={"GET", "POST"}, name="carnet_route" )
     */
    public function carnet(User $user) : Response
    {


        return $this->render('user/carnet.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/ma-liste/{id}", methods={"GET", "POST"}, name="maliste" )
     */
    public function maliste(User $user, RouteLikeRepository $rlrepo)
    {
        $route = $rlrepo->findRouteLikeByUser($user->getId());

        return $this->render('user/maliste.html.twig', [
            'user' => $user,
            'routes' => $route
        ]);
    }

    /**
     * @Route("/route/delete/{id}", name="delete_route", methods={"GET", "POST"})
     */
    public function deleteItem(Request $request, RouteLike $routelike): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($routelike);
            $entityManager->flush();

            return $this->redirectToRoute('carnet_de_route');
    }


    /**
     * @Route("/deleteplace/{id}", name="place_delete", methods={"POST", "GET"})
     */
    public function deletePlace(Place $place): Response
    {


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();


        return $this->redirectToRoute('home');
    }

}
