<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Game21\Player;
use App\Game21\Bank;
use App\Game21\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route("/game/initiate", name: "game_init")]
    public function gameInit(SessionInterface $session): Response
    {
        $session->get('session');
        $session->invalidate();
        $session->start();

        $card = new CardGraphic();
        $deck = new DeckOfCards();
        $game = new Game21();
        $player = new Player("player 1");
        $bank = new Bank();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $deck->add(new CardGraphic($suite, $i));
            }
        }

        $game->addCardDeck($deck);
        $game->addPlayer($player);
        $game->addPlayer($bank);

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/play", name: "game_play")]
    public function gamePlay(SessionInterface $session): Response
    {
        $data = [
            "currentPlayer" => "",
            "playerCards" => [],
            "playerCardTotal" => 0,
            "bankCards" => [],
            "bankCardTotal" => 0,
            "winner" => "",
            "loser" => ""
        ];

        $game = $session->get("game");

        if ($game instanceof Game21) {
            $currentPlayer = $game->getCurrentPlayerInQueue();
            $data["currentPlayer"] = $currentPlayer->name;
            $winStatus = $game->checkWinStatus();
            $data["winner"] = $winStatus["winner"];
            $data["loser"] = $winStatus["loser"];
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

        return $this->render('game21/game21_play.html.twig', $data);
    }

    #[Route("/game/draw", name: "game_draw_card", methods: ["POST"])]
    public function gameDraw(SessionInterface $session): Response
    {
        $totalCardsPlayed = 0;
        $game = $session->get("game");

        if ($game instanceof Game21) {
            $currentPlayer = $game->getCurrentPlayerInQueue();
            $session->set("player_cards", $game->drawNewCard());
            $session->set("player_card_total", $game->getHandTotal($currentPlayer));
            $totalCardsPlayed = $currentPlayer->getHandCount();
        }

        if ($totalCardsPlayed >=2) {
            $game->getNextPlayerInQueue();
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/bank", name: "game_bank", methods: ["POST"])]
    public function gameBank(SessionInterface $session): Response
    {
        $game = $session->get("game");

        if ($game instanceof Game21) {
            $currentPlayer = $game->getCurrentPlayerInQueue();

            if ($currentPlayer->name != "bank") {
                $game->getNextPlayerInQueue();
                $currentPlayer = $game->getCurrentPlayerInQueue();
            }

            $game->drawNewCard();
            $total = $game->getHandTotal($currentPlayer);

            if ($total < 10) {
                $game->drawNewCard();
            }

            $session->set("bank_cards", $game->getPlayerHand());
            $session->set("bank_card_total", $game->getHandTotal($currentPlayer));
        }

        return $this->redirectToRoute('game_play');
    }

}
