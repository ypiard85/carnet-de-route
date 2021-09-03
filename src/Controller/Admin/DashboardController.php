<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Sujet;
use App\Entity\Comment;
use App\Entity\Actualites;
use App\Entity\SujetResponse;
use App\Entity\ForumCategorie;
use App\Controller\Admin\PlaceCrudController;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(PlaceCrudController::class)->generateUrl());
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Important');
        yield MenuItem::linkToCrud('Lieux', 'fas fa-location-arrow', Place::class);
        yield MenuItem::linkToCrud('Thêmes', 'fas fa-location-arrow', Categorie::class);
        yield MenuItem::linkToCrud('Villes', 'fas fa-city', City::class);
        yield MenuItem::linkToCrud('Commentaires places', 'fas fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Sujets', 'fas fa-align-justify', Sujet::class);
        yield MenuItem::linkToCrud('Commentaires sujets', 'fas fa-comment-dots', SujetResponse::class);
        yield MenuItem::linkToCrud('Categories sujets', 'fas fa-book', ForumCategorie::class);
        yield MenuItem::linkToCrud('Actualites', 'far fa-newspaper', Actualites::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
    }


}
