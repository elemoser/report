<?php

namespace App\Controller;

// use App\Card\Card;
// use App\Card\CardGraphic;
// use App\Card\CardCollection;
// use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Game21Controller extends AbstractController
{
    #[Route("/game", name: "game_home")]
    public function gameHome(): Response
    {
        return $this->render('game21/game21.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('game21/game21_doc.html.twig');
    }

    #[Route("/game/play", name: "game_play")]
    public function gamePlay(): Response
    {
        // render a template
        return $this->render('game21/game21_doc.html.twig');
    }

    #[Route("/game/draw", name: "game_draw_card", methods: ["POST"])]
    public function gameDraw(): Response
    {
        // render a template
        return $this->redirectToRoute('game_play');
    }

}
