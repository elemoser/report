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
    private $game;

    /**
     * @var DeckOfCards $deck
     */
    private $deck;

    /**
     * Set ut for tests.
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

    /**
     * Test methods computeHandTotal() and getHandTotal()
     * when cards include an Ace and result with 14 is lower than 21.
     */
    public function testHandTotalWithAceLower(): void
    {
        $cards = [
            new Card("ruter", 1),
            new Card("klöver", 4)
        ];

        $player = new Player("player");

        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $this->game->addPlayer($player);
        $playerHandTotal = $this->game->getHandTotal($player);
        $this->assertIsInt($playerHandTotal);
        $this->assertEquals(18, $playerHandTotal);
    }

    /**
     * Test methods computeHandTotal() and getHandTotal()
     * when cards include an Ace and result with 14 is greater than 21.
     */
    public function testHandTotalWithAceGreater(): void
    {
        $cards = [
            new Card("ruter", 1),
            new Card("spader", 11)
        ];

        $player = new Player("player");

        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $this->game->addPlayer($player);
        $playerHandTotal = $this->game->getHandTotal($player);
        $this->assertIsInt($playerHandTotal);
        $this->assertEquals(12, $playerHandTotal);
    }

    /**
     * Test methods computeHandTotal() and getHandTotal()
     * when cards include no Ace and result is lower than 21.
     */
    public function testHandTotalWithoutAceLower(): void
    {
        $cards = [
            new Card("ruter", 5),
            new Card("klöver", 3)
        ];

        $player = new Player("player");

        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $this->game->addPlayer($player);
        $playerHandTotal = $this->game->getHandTotal($player);
        $this->assertIsInt($playerHandTotal);
        $this->assertEquals(8, $playerHandTotal);
    }

    /**
     * Test methods computeHandTotal() and getHandTotal()
     * when cards include no Ace and result is greater than 21.
     */
    public function testHandTotalWithoutAceGreater(): void
    {
        $cards = [
            new Card("hjärter", 13),
            new Card("klöver", 12)
        ];

        $player = new Player("player");

        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $this->game->addPlayer($player);
        $playerHandTotal = $this->game->getHandTotal($player);
        $this->assertIsInt($playerHandTotal);
        $this->assertEquals(25, $playerHandTotal);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total < 21 and bank's card total = 0
     */
    public function testNoLoserStatusYet(): void
    {
        $cards = [
            new Card("spader", 1),
            new Card("hjärter", 9)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $bank = new Player("bank");

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertNotEquals("player", $output["loser"]);
        $this->assertNotEquals("bank", $output["loser"]);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total > 21 and bank's card total = 0
     */
    public function testPlayersHandTotalGreaterThan21(): void
    {
        $cards = [
            new Card("hjärter", 13),
            new Card("klöver", 9)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $bank = new Player("bank");

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertEquals("player", $output["loser"]);
        $this->assertEquals("bank", $output["winner"]);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total < 21 and closer to 21 than bank
     */
    public function testPlayersHandTotalCloserTo21(): void
    {
        $cards = [
            new Card("ruter", 1),
            new Card("klöver", 2)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $cards = [
            new Card("hjärter", 6),
            new Card("hjärter", 9)
        ];

        $bank = new Player("bank");
        foreach ($cards as $card) {
            $bank->addCard($card);
        }

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertEquals("player", $output["winner"]);
        $this->assertEquals("bank", $output["loser"]);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total < 21 and farther to 21 than bank
     */
    public function testBanksHandTotalCloserTo21(): void
    {
        $cards = [
            new Card("spader", 2),
            new Card("hjärter", 2)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $cards = [
            new Card("spader", 1),
            new Card("klöver", 6)
        ];

        $bank = new Player("bank");
        foreach ($cards as $card) {
            $bank->addCard($card);
        }

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertEquals("player", $output["loser"]);
        $this->assertEquals("bank", $output["winner"]);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total < 21 and bank's card total > 21
     */
    public function testBanksHandTotalGreaterThan21(): void
    {
        $cards = [
            new Card("hjärter", 10),
            new Card("klöver", 7)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $bank = new Player("bank");

        $cards = [
            new Card("spader", 13),
            new Card("spader", 11)
        ];

        foreach ($cards as $card) {
            $bank->addCard($card);
        }

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertEquals("player", $output["winner"]);
        $this->assertEquals("bank", $output["loser"]);
    }

    /**
     * Test methods winStatus().
     * Case: Player's card total < 21 and equal to bank's card total
     */
    public function testHandTotalEqual(): void
    {
        $cards = [
            new Card("ruter", 10),
            new Card("spader", 6)
        ];

        $player = new Player("player");
        foreach ($cards as $card) {
            $player->addCard($card);
        }

        $cards = [
            new Card("hjärter", 7),
            new Card("klöver", 9)
        ];

        $bank = new Player("bank");
        foreach ($cards as $card) {
            $bank->addCard($card);
        }

        $this->game->addPlayer($player);
        $this->game->addPlayer($bank);

        $output = $this->game->checkWinStatus();
        $this->assertIsArray($output);
        $this->assertContainsOnly('string', $output);
        $this->assertEquals("player", $output["loser"]);
        $this->assertEquals("bank", $output["winner"]);
    }
}
