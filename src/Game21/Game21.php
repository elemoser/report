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
        $this->next = null;
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
        if (is_int($this->next) && $this->next < $this->queue) {
            $this->next = $this->next + 1;
        } else {
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

        for ($i = 0; $i <= $drawnCards; $i++) {
            $currentPlayer->addCard($drawnCards[$i]);
        }

        return $this->getPlayerHand();
    }

    /**
     * @return int
     */
    protected function computeHandTotal(Player $player)
    {

        // $player->getHandValues()

        // sum hand values
        // convert
        // ess = 1 or 14
        // king = 13
        // queen = 12
        // jack = 11

        // return total
    }

    public function checkWinStatus(): void
    {
        // foreach $player in $queue
        // $this->computeHandTotal()
        // and save in $report = ["player name" => total]

        // for item in $report
        // if player's hand > 21
        // that player looses

        // check closest to 21
        // by doing 21 - total for all items in $report
        // and save in $diff ["player name" => 21 - total]
        
        // sort or get min() from array ?
        // and by doing so get player that is closest to 21
    }
}