<?php

namespace App\Controller;

use App\Entity\Actualites;
use App\Repository\PlaceRepository;
use App\Repository\SujetRepository;
use App\Repository\ActualitesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request, PlaceRepository $placerepo, ActualitesRepository $acturepo, SujetRepository $sujetrepo)
    {

        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('app_login')];
        $urls[] = ['loc' => $this->generateUrl('app_register')];

        foreach($placerepo->findAll() as $place){
                $urls[] = [
                    'loc' => $this->generateUrl('place_show', [
                        'id' => $place->getId()
                    ]),
                    'lastmod' => $place->getUpdatedAt()->format('Y-m-d')
                ];
            }
        foreach($acturepo->findAll() as $actu){
                $urls[] = [
                    'loc' => $this->generateUrl('actualites_show', [
                        'title' => $actu->getTitle()
                    ]),
                ];
            }
        foreach($sujetrepo->findAll() as $sujet){
                $urls[] = [
                    'loc' => $this->generateUrl('sujet_show', [
                        'id' => $sujet->getId(),
                        'title' => $sujet->getTitle()
                    ]),
                ];
            }

            $response = new Response(
                $this->renderView('sitemap/index.html.twig',
                    [
                    'urls' => $urls,
                    'hostname' => $hostname
                    ]),
                200
            );

            $response->headers->set('Content-Type', 'text/xml');

            return $response;

    }
}
