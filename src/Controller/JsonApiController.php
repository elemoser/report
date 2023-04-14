<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardCollection;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class JsonApiController extends AbstractController
{
    #[Route("/api/deck/init", name: "api_deck_init")]
    public function initCallback(
        SessionInterface $session
    ): Response {
        $card = new Card();
        $deck = new DeckOfCards();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new Card($suite, $i));
            }
        }

        $session->set("deck", $deck);

        return $this->redirectToRoute('api_deck_get');
    }

    #[Route("/api/deck", name: "api_deck_get", methods: ['GET'])]
    public function apiDeckGet(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $data = [
            "DeckSize" => $deck->getCount(),
            "CardsInDeck" => $deck->getStrings(),
            "CardColors" => $deck->getColors()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle_post", methods: ['POST'])]
    public function apiDeckShufflePost(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $deck->shuffle();

        $session->set("deck", $deck);

        return $this->redirectToRoute('api_deck_get');
    }


    #[Route("/api/deck/draw", name: "api_deck_draw_post", methods: ['POST'])]
    public function apiDeckDrawPost(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $deckSize = $deck->getCount();

        if ($deckSize >= 1) {
            $randomCards = new CardCollection();
            $randomCard = $deck->draw();
            $randomCards->add($randomCard[0]);

            $session->set("drawn_cards", $randomCards);
            $session->set("deck", $deck);
            return $this->redirectToRoute('api_deck_draw_get');
        }

        return $this->redirectToRoute('api_deck_init');
    }

    #[Route("/api/deck/draw/num", name: "api_deck_draw_num_post", methods: ["POST"])]
    public function apiDeckDrawNumPost(
        Request $request,
        SessionInterface $session
    ): Response {

        $num = $request->request->get('num_cards');

        $deck = $session->get("deck");
        $deckSize = $deck->getCount();

        // Draw random cards
        if (!$num) {
            $num = 2;
        }

        if ($deckSize >= $num) {
            $randomCards = new CardCollection();
            $randomCardsFromDeck = $deck->draw($num);

            foreach ($randomCardsFromDeck as $randCard) {
                $randomCards->add($randCard);
            }

            $session->set("drawn_cards", $randomCards);
            $session->set("deck", $deck);

            return $this->redirectToRoute('api_deck_draw_get');
        }

        return $this->redirectToRoute('api_deck_init');
    }

    #[Route("/api/deck/draw", name: "api_deck_draw_get", methods: ['GET'])]
    public function apiDeckDrawGet(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");
        $drawnCards = $session->get("drawn_cards");

        $data = [
            "DeckSize" => $deck->getCount(),
        ];

        // var_dump($drawnCards);

        // if (count($drawnCards) === 1) {
        //     $data["CardDrawn"] = $drawnCards[0]->getAsString();
        //     $data["CardsColor"] = $drawnCards[0]->getColor();
        // } else {
        $data["CardsDrawn"] = $drawnCards->getStrings();
        $data["CardsColor"] = $drawnCards->getColors();
        // }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
