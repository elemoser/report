<?php

namespace App\Controller;

use App\Game21\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Game21ApiController extends AbstractController
{
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
}
