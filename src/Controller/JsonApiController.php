<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardCollection;
use App\Card\DeckOfCards;
use App\Game21\Game21;
use App\Repository\BookRepository;
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
            "DeckSize" => 0,
            "CardsInDeck" => [],
            "CardColors" => []
        ];

        if ($deck instanceof DeckOfCards) {
            $data = [
                "DeckSize" => $deck->getCount(),
                "CardsInDeck" => $deck->getStrings(),
                "CardColors" => $deck->getColors()
            ];
        }

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

        if ($deck instanceof DeckOfCards) {
            $deck->shuffle();
        }

        $session->set("deck", $deck);

        return $this->redirectToRoute('api_deck_get');
    }


    #[Route("/api/deck/draw", name: "api_deck_draw_post", methods: ['POST'])]
    public function apiDeckDrawPost(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $deckSize = 0;

        if ($deck instanceof DeckOfCards) {
            $deckSize = $deck->getCount();

            if ($deckSize >= 1) {
                $randomCards = new CardCollection();
                $randomCard = $deck->draw();
                $randomCards->add($randomCard[0]);

                $session->set("drawn_cards", $randomCards);
                $session->set("deck", $deck);
                return $this->redirectToRoute('api_deck_draw_get');
            }
        }

        return $this->redirectToRoute('api_deck_init');
    }

    #[Route("/api/deck/draw/num", name: "api_deck_draw_num_post", methods: ["POST"])]
    public function apiDeckDrawNumPost(
        Request $request,
        SessionInterface $session
    ): Response {

        $num = (int) $request->request->get('num_cards');

        $deck = $session->get("deck");

        if (!$num) {
            $num = 2;
        }

        if ($deck instanceof DeckOfCards) {
            $deckSize = $deck->getCount();

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
            "DeckSize" => 0,
            "CardsDrawn" => [],
            "CardsColor" => []
        ];

        if ($deck instanceof DeckOfCards) {
            $data = [
                "DeckSize" => $deck->getCount(),
            ];
        }

        if ($drawnCards instanceof CardCollection) {
            $data["CardsDrawn"] = $drawnCards->getStrings();
            $data["CardsColor"] = $drawnCards->getColors();
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/game", name: "api_game21", methods: ['POST'])]
    public function apiGame21(
        SessionInterface $session
    ): Response {
        $data = [
            "currentPlayer" => "",
            "playerCards" => [],
            "playerCardTotal" => 0,
            "bankCards" => [],
            "bankCardTotal" => 0,
            "winner" => "",
            "loser" => ""
        ];

        $game = null;

        if ($session->has("game")) {
            $game = $session->get("game");
        }

        if ($game instanceof Game21) {
            $currentPlayer = $game->getCurrentPlayerInQueue();
            $data["currentPlayer"] = $currentPlayer->name;
            $winStatus = $game->checkWinStatus();

            if ($winStatus["loser"]) {
                $data["winner"] = $winStatus["winner"];
                $data["loser"] = $winStatus["loser"];
            }
        }

        if ($session->has("player_cards")) {
            $data["playerCards"] = $session->get("player_cards");
        }

        if ($session->has("player_card_total")) {
            $data["playerCardTotal"] = $session->get("player_card_total");
        }

        if ($session->has("bank_cards")) {
            $data["bankCards"] = $session->get("bank_cards");
        }

        if ($session->has("bank_card_total")) {
            $data["bankCardTotal"] = $session->get("bank_card_total");
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/books", name: "api_library")]
    public function apiLibrary(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book", name: "api_book_isbn_post", methods: "POST")]
    public function apiBook(
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $isbn = $request->request->get("isbn");

        if (!$isbn) {
            $books = $bookRepository->findAll();
            $isbn = $books[0]->getISBN();
        }

        // return new Response($isbn);
        return $this->redirectToRoute('api_book_isbn', ["isbn" => $isbn]);
    }

    #[Route("/api/library/book/{isbn}", name: "api_book_isbn")]
    public function apiBookByISBN(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository->findBy(
            ['ISBN' => $isbn]
        );

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for ISBN '.$isbn
            );
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
