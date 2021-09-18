<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Place;
use App\Form\UserType;
use App\Entity\RouteLike;
use App\Form\UserEditType;
use App\Repository\LikeRepository;
use App\Repository\UserRepository;
use App\Repository\PlaceRepository;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\RouteLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/userslist", name="users_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function userlist(UserRepository $userrepo)
    {

        return $this->render('user/userslist.html.twig', [
            'users' => $userrepo->findAll()
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
    public function deletePlace(Place $place, ImageRepository $imgrepo): Response
    {
        $noms = $imgrepo->findBy(['place' => $place]);

        for($i = 0; $i < count($noms); $i++ ){
            unlink($this->getParameter('image_place'). '/' .$noms[$i]->getName());
        }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $this->getUser()->getId() ]);
    }

     /**
     * @Route("/deleteplacelike/{id}", name="placelike_delete", methods={"POST", "GET"})
     */
    public function deleteplaceLike(RouteLike $routelike, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($routelike);
        $entityManager->flush();

        $this->addFlash('message', 'Le lieu a été enlevé de votre carnet de route' );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }

    /**
     * @Route("/{pseudo}/edit", name="user_edit", methods={"GET","POST"} )
     */
    public function edit(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush($user);

            $this->addFlash('message', 'Votre profil à été mis à jour');

            return $this->redirectToRoute('user_show', [ 'id' => $this->getUser()->getId() ] );
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editprofil/{id}", name="userprofil_edit", methods={"GET", "POST"} )
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(User $user, UserEditType $useredittype, Request $request)
    {

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('message', 'Utilisateur modifier');

            return $this->redirectToRoute('users_list');

        }

        return $this->render('user/profiledit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

}
