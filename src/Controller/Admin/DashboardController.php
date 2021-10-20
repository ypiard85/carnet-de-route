<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Sujet;
use App\Entity\Comment;
use App\Entity\Categorie;
use App\Entity\Actualites;
use App\Entity\SujetResponse;
use App\Entity\ForumCategorie;
use App\Controller\Admin\PlaceCrudController;
use App\Entity\PolitiqueConfidentialiteEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
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

        return parent::index();
    }

    public function configureMenuItems(): iterable
    {
        return[
         MenuItem::section('Important'),
         MenuItem::linkToCrud('Lieux', 'fas fa-location-arrow', Place::class),
         MenuItem::linkToCrud('Thêmes', 'fab fa-ethereum', Categorie::class),
         MenuItem::linkToCrud('Villes', 'fas fa-city', City::class),
         MenuItem::linkToCrud('Commentaires places', 'fas fa-comments', Comment::class),
         MenuItem::linkToCrud('Sujets', 'fas fa-align-justify', Sujet::class),
         MenuItem::linkToCrud('Commentaires sujets', 'fas fa-comment-dots', SujetResponse::class),
         MenuItem::linkToCrud('Categories sujets', 'fas fa-book', ForumCategorie::class),
         MenuItem::linkToCrud('Actualites', 'far fa-newspaper', Actualites::class),
         MenuItem::linkToCrud('Users', 'fas fa-user', User::class),
         MenuItem::linkToCrud('Politique', 'fas fa-book', PolitiqueConfidentialiteEntity::class),
         MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // ...

            // the argument is the name of any valid Symfony translation domain
            ->setTranslationDomain('admin');
    }

}
