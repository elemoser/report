<?php

namespace App\Game21;

use App\Card\Card;

/**
 * Test cases for class Game21.
 */
class Game21TestWinStatus extends Game21Test
{
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
