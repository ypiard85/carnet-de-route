<?php

namespace App\Controller;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{



    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        $tomorrow = Carbon::now('Asia/Seoul');
        $h = $tomorrow->isoFormat('h');
        $m = $tomorrow->isoFormat('mm');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'h' => $h, 'm' => $m,
        ]);
    }
}
