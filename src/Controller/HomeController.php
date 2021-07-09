<?php

namespace App\Controller;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{



    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, cityRepository $city): Response
    {

        $tomorrow = Carbon::now('Asia/Seoul');
        $h = $tomorrow->isoFormat('h');
        $m = $tomorrow->isoFormat('mm');

        if($request){
            $filter = [
            $request->get('q'),
            $request->get('city'),
            $request->get('aime')
            ];
        }

        $villes = $city->CityByName();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'h' => $h, 'm' => $m,
            'filter' => $filter,
            'villes' => $villes
        ]);
    }
}
