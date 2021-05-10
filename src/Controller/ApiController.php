<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PlaceRepository;
use App\Repository\RouteLikeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/place", name="api_post", methods={"GET"} )
     */
    public function index(PlaceRepository $placerepo, NormalizerInterface $normalizer): Response
    {

        $placerepo->findAll();

    }
}
