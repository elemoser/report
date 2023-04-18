<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardCollection;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
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
    public function cardDeck(): Response
    {
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        $data = [
            "deckCount" => $deck->getCount(),
            "deckStrings" => $deck->getStrings(),
            "deckColors" => $deck->getColors()
        ];

        return $this->render('card_game/card_deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function cardDeckShuffle(): Response
    {
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        $deck->shuffle();

        $data = [
            "deckCount" => $deck->getCount(),
            "deckStrings" => $deck->getStrings(),
            "deckColors" => $deck->getColors()
        ];

        return $this->render('card_game/card_deck_shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function cardDeckDraw(): Response
    {
        // Create a deck of cards
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        // Remove random card from deck
        $randomCardFromDeck = $deck->draw();

        $data = [
            "cardString" => $randomCardFromDeck[0]->getAsString(),
            "cardColor" => $randomCardFromDeck[0]->getColor(),
            "deckCount" => $deck->getCount()
        ];

        return $this->render('card_game/card_deck_draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num")]
    public function cardDeckDrawNum(int $num): Response
    {
        // Create a deck of cards
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        // Draw random cards
        $randomCards = new CardCollection();
        $randomCardsFromDeck = $deck->draw($num);

        foreach ($randomCardsFromDeck as $randCard) {
            $randomCards->add($randCard);
        }

        $data = [
            "cardStrings" => $randomCards->getStrings(),
            "cardColors" => $randomCards->getColors(),
            "deckCount" => $deck->getCount()
        ];

        return $this->render('card_game/card_deck_draw_num.html.twig', $data);
    }
}
