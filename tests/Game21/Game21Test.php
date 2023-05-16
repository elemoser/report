<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\DeckOfCards;

/**
 * Test cases for class Game21.
 */
class Game21Test extends TestCase
{
    /**
     * @var Game21 $game
     */
    protected $game;

    /**
     * @var DeckOfCards $deck
     */
    protected $deck;

    /**
     * Set up for tests.
     */
    protected function setUp(): void
    {
        $this->game = new Game21();

        $this->deck = new DeckOfCards();
        $card = new Card();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $this->deck->add(new Card($suite, $i));
            }
        }
    }

    /**
     * Construct object and verify the default values are set correctly.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\Game21\Game21", $this->game);
    }


    /**
     * Test methods altering the queue of players in the game.
     */
    public function testPlayerQueueInGame(): void
    {
        $playerOne = new Player("player 1");
        $playerTwo = new Player("player 2");

        $this->game->addPlayer($playerOne);
        $this->game->addPlayer($playerTwo);

        $current = $this->game->getCurrentPlayerInQueue();
        $this->assertInstanceOf("\App\Game21\Player", $current);
        $this->assertEquals($playerOne->name, $current->name);

        $next = $this->game->getNextPlayerInQueue();
        $this->assertInstanceOf("\App\Game21\Player", $next);
        $this->assertEquals($playerTwo->name, $next->name);

        $nextNext = $this->game->getNextPlayerInQueue();
        $this->assertInstanceOf("\App\Game21\Player", $nextNext);
        $this->assertEquals($playerOne->name, $nextNext->name);
    }

    /**
     * Test method getPlayerHand() and verify it is empty.
     */
    public function testCurrentPlayerHandIsEmpty(): void
    {
        $playerOne = new Player("player 1");
        $this->game->addPlayer($playerOne);
        $currentHand = $this->game->getPlayerHand();

        $this->assertIsArray($currentHand);
        $this->assertEmpty($currentHand);
        $this->assertCount(0, $currentHand);
    }

    /**
     * Adding complete deck of cards to Game and verify new cards can be drawn.
     */
    public function testCardDeckInGame(): void
    {
        $playerOne = new Player("player 1");
        $this->game->addPlayer($playerOne);
        $this->game->addCardDeck($this->deck);
        $currentHand = $this->game->drawNewCard();

        $this->assertIsArray($currentHand);
        $this->assertNotEmpty($currentHand);
        $this->assertCount(1, $currentHand);
        $this->assertContainsOnly('string', array_keys($currentHand));
        $allCardKeys = array_keys($currentHand);
        $firstCard = $currentHand[$allCardKeys[0]];
        $this->assertIsArray($firstCard);
        $this->assertContainsOnly('string', $firstCard);
    }
}
