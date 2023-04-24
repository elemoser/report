<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardCollection;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route("/card/init/{route}", name: "card_init")]
    public function cardInit(
        string $route,
        SessionInterface $session
    ): Response {
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        $session->set("deck", $deck);

        return $this->redirectToRoute($route);
    }

    #[Route("/card", name: "card_home")]
    public function cardHome(): Response
    {
        $card = new CardGraphic("hjärter", 1);

        $data = [
            "cardString" => $card->getAsString()
        ];

        return $this->render('card_game/card.html.twig', $data);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");

        $data = [
            "deckCount" => 0,
            "deckStrings" => [],
            "deckColors" => []
        ];

        if ($deck instanceof DeckOfCards) {
            $data["deckCount"] = $deck->getCount();
            $data["deckStrings"] = $deck->getStrings();
            $data["deckColors"] = $deck->getColors();
        }

        return $this->render('card_game/card_deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function cardDeckShuffle(SessionInterface $session): Response
    {
        $deck = $session->get("deck");

        $data = [
            "deckCount" => 0,
            "deckStrings" => [],
            "deckColors" => []
        ];

        if ($deck instanceof DeckOfCards) {
            $deck->shuffle();
            $data["deckCount"] = $deck->getCount();
            $data["deckStrings"] = $deck->getStrings();
            $data["deckColors"] = $deck->getColors();
        }

        return $this->render('card_game/card_deck_shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function cardDeckDraw(SessionInterface $session): Response
    {
        // Create a deck of cards
        $deck = $session->get("deck");

        $data = [
            "deckCount" => 0,
            "deckString" => [],
            "deckColor" => []
        ];

        // Remove random card from deck
        if ($deck instanceof DeckOfCards) {
            if ($deck->getCount() < 1) {
                return $this->redirect('/card/init/card_deck_draw');
            }

            $randomCardFromDeck = $deck->draw();
            $data["cardString"] = $randomCardFromDeck[0]->getAsString();
            $data["cardColor"] = $randomCardFromDeck[0]->getColor();
            $data["deckCount"] = $deck->getCount();
        }

        return $this->render('card_game/card_deck_draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num")]
    public function cardDeckDrawNum(
        int $num,
        SessionInterface $session
    ): Response {
        // Create a deck of cards
        $deck = $session->get("deck");

        $data = [
            "deckCount" => 0,
            "cardStrings" => [],
            "cardColors" => []
        ];

        // Draw random cards
        if ($deck instanceof DeckOfCards) {
            if ($deck->getCount() < $num) {
                return $this->redirect('/card/init/card_deck_draw_num');
            }

            $randomCards = new CardCollection();
            $randomCardsFromDeck = $deck->draw($num);

            foreach ($randomCardsFromDeck as $randCard) {
                $randomCards->add($randCard);
            }

            $data["cardStrings"] = $randomCards->getStrings();
            $data["cardColors"] = $randomCards->getColors();
            $data["deckCount"] = $deck->getCount();
        }

        return $this->render('card_game/card_deck_draw_num.html.twig', $data);
    }
}
