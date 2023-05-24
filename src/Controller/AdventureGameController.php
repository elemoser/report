<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureGameController extends AbstractController
{
    #[Route('/proj', name: 'proj_home')]
    public function projectHome(): Response
    {
        return $this->render('adventure/index.html.twig');
    }

    #[Route('/proj/play', name: 'proj_game')]
    public function projectPlay(): Response
    {
        return $this->render('adventure/game.html.twig');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function projectAbout(): Response
    {
        return $this->render('adventure/about.html.twig');
    }

    #[Route('/proj/api', name: 'proj_api')]
    public function projectApi(): Response
    {
        return $this->render('adventure/api.html.twig');
    }
}
