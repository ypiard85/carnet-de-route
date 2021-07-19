<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Repository\CityRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
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
}
