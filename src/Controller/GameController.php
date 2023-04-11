<?php

namespace App\Controller;

// use App\Card\Card;
use App\Card\CardGraphic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController {
    #[Route("/card", name: "card_home")]
    public function card_home(): Response
    {
        $card = new CardGraphic();

        $data = [
            "card" => $card->draw(),
            "cardString" => $card->getAsString()
        ];

        return $this->render('card_game/card.html.twig', $data);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function card_deck(): Response
    {
        return $this->render('card_game/card_deck.html.twig');
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function card_deck_shuffle(): Response
    {
        return $this->render('card_game/card_deck_shuffle.html.twig');
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function card_deck_draw(): Response
    {
        return $this->render('card_game/card_deck_draw.html.twig');
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num")]
    public function card_deck_draw_num(int $num): Response
    {
        return $this->render('card_game/card_deck_draw_num.html.twig');
    }
}