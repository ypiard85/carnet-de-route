<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Comment;
use App\Controller\PlaceController;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(PlaceCrudController::class)->generateUrl());
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Important');
        yield MenuItem::linkToCrud('Places', 'fa fa-file-pdf', Place::class);
        yield MenuItem::linkToCrud('Villes', 'fa fa-file-pdf', City::class);
        yield MenuItem::linkToCrud('Images', 'fa fa-file-pdf', Image::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-file-pdf', Comment::class);
    }


}
