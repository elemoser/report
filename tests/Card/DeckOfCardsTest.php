<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    private $deck;

    protected function setUp(): void
    {
        $this->deck = new DeckOfCards();
        $card = new Card();

        foreach ($card->suites as $suite) {
            for ($i = $card->minValue; $i <= $card->maxValue; $i++) {
                $this->deck->add(new Card($suite, $i));
            }
        }
    }

    /**
     * Construct object without arguments and verify the default values are set correctly.
     */
    public function testCreateObjectNoArguments()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
    }

    /**
     * Test isCompleteDeck() method and verify output is the expected Boolean.
     */
    public function testCompleteDeck()
    {
        $this->assertTrue($this->deck->isCompleteDeck());
    }

    /**
     * Test isCompleteDeck() method and verify output is the expected Boolean.
     */
    public function testIncompleteDeck()
    {
        $this->deck->draw(1);

        $this->assertFalse($this->deck->isCompleteDeck());
    }
}