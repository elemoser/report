<?php

namespace App\Game21;

use App\Game21\Player;
use App\Card\DeckOfCards;

use function PHPUnit\Framework\isInstanceOf;

class Game21
{
    /**
     * @var array<Player> $queue
     */
    protected $queue;

    /**
     * @var int $next
     */
    protected $next;

    /**
     * @var DeckOfCards $deck
     */
    protected $deck;

    public function __construct()
    {
        $this->queue = [];
        $this->next = -1;
    }

    public function addCardDeck(DeckOfCards $deck): void
    {
        $this->deck = $deck;
    }

    public function addPlayer(Player $player): void
    {
        array_push($this->queue, $player);
    }

    // public function removePlayer(Player $player): void

    /**
     * @return int
     */
    public function getNextInQueue()
    {
        if ($this->next < count($this->queue)) {
            $this->next = $this->next + 1;
        }

        if ($this->next < count($this->queue)) {
            $this->next = 0;
        }

        return $this->next;
    }

    /**
     * @return array<string>
     */
    public function getPlayerHand()
    {
        $currentPlayer = $this->queue[$this->next];
        $currentHand = [];

        if ($currentPlayer instanceof Player) {
            $currentHand = $currentPlayer->getHandAsString();
        }

        return $currentHand;
    }

    /**
     * @return array<string>
     */
    public function drawNewCard(int $num = 1)
    {
        $currentPlayer = $this->queue[$this->next];

        $drawnCards = $this->deck->draw($num);

        $drawnCardsLength = count($drawnCards);

        for ($i = 0; $i <= $drawnCardsLength; $i++) {
            $currentPlayer->addCard($drawnCards[$i]);
        }

        return $this->getPlayerHand();
    }

    /**
     * @return array<string, int>
     */
    protected function getPlayerHandValues(Player $player)
    {
        $handValues = $player->getHandValues();

        $sumTotals = ["ess is 1" => 0, "ess is 14" => 0];

        foreach ($handValues as $cardValues) {
            switch ($cardValues[1]) {
                case "ess":
                    $sumTotals["ess is 1"] += 1;
                    $sumTotals["ess is 14"] += 14;
                    break;
                case "kung":
                    $sumTotals["ess is 1"] += 13;
                    $sumTotals["ess is 14"] += 13;
                    break;
                case "dam":
                    $sumTotals["ess is 1"] += 12;
                    $sumTotals["ess is 14"] += 12;
                    break;
                case "knekt":
                    $sumTotals["ess is 1"] += 11;
                    $sumTotals["ess is 14"] += 11;
                    break;
                default:
                    $sumTotals["ess is 1"] += intval($cardValues[1]);
                    $sumTotals["ess is 14"] += intval($cardValues[1]);
            }
        }

        return $sumTotals;
    }

    /**
     * @return int
     */
    protected function computeHandTotal(Player $player)
    {
        $sumTotals = $this->getPlayerHandValues($player);

        // if ($sumTotals["ess is 1"] > 21 && $sumTotals["ess is 14"] > 21)
        // if ($sumTotals["ess is 1"] < 21 && $sumTotals["ess is 14"] > 21)
        $result = $sumTotals["ess is 1"];

        if ($sumTotals["ess is 1"] > 21 && $sumTotals["ess is 14"] < 21) {
            $result = $sumTotals["ess is 14"];
        }

        if ($sumTotals["ess is 1"] < 21 && $sumTotals["ess is 14"] < 21) {
            $one = 21 - $sumTotals["ess is 1"];
            $fourteen = 21 - $sumTotals["ess is 14"];
            // if ($a == $b || $a < $b)
            $result = $sumTotals["ess is 1"];

            if ($one > $fourteen) {
                $result = $sumTotals["ess is 14"];
            }
        }

        return $result;
    }

        /**
     * @return array<string, string>
     */
    public function checkWinStatus()
    {
        $report = [];
        $winner = [""];
        $loser = [""];

        foreach ($this->queue as $player) {
            if (count($player->getHandAsString())) {
                $report[$player->name] = $this->computeHandTotal($player);
            }
        }

        foreach ($report as $key=>$value) {
            if ($value < 21) {
                array_push($winner, $key);
            }

            if ($value < 21) {
                array_push($loser, $key);
            }
        }

        if (count($winner) > 1) {
            $diff = [];
            foreach ($report as $key=>$value) {
                $diff[$key] = 21 - $value;
            }
            sort($diff);
            $winner = array_keys($diff);

            if (count($diff) != count(array_unique($diff))) {
                $winner = ["bank"];
                unset($diff["bank"]);
                $loser = array_keys($diff);
            }
        }

        return ["winner" => $winner[0], "loser" => $loser[0]];
    }
}
