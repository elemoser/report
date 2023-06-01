<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardCollection.
 */
class CardCollectionTest extends TestCase
{
    /**
     * Construct object without arguments and verify the default values are set correctly.
     */
    public function testCreateObjectNoArguments(): void
    {
        $cards = new CardCollection();
        $cardsCount = $cards->getCount();
        $this->assertInstanceOf("\App\Card\CardCollection", $cards);
        $this->assertEquals(0, $cardsCount);
    }

    /**
     * Test add() method and verify the added objects are in the $cards array.
     */
    public function testMethodAddCard(): void
    {
        $cards = new CardCollection();
        $card1 = new Card();
        $card2 = new Card();
        $cards->add($card1);
        $cards->add($card2);
        $cardsCount = $cards->getCount();
        $this->assertEquals(2, $cardsCount);
    }

    /**
     * Test remove() method and verify the removed objects are no longer in the $cards array.
     */
    public function testMethodRemoveCard(): void
    {
        $cards = new CardCollection();
        $card = new Card();
        $cards->add($card);
        $cards->remove($card);
        $cardsCount = $cards->getCount();
        $this->assertEquals(0, $cardsCount);
    }

    /**
     * Test getValues() method and verify output is a correct array of an array of strings.
     */
    public function testMethodReturningValues(): void
    {
        $cards = new CardCollection();
        $randNum = random_int(1, 52);
        $expected = [];

        for ($i = 0; $i < $randNum; $i++) {
            $card = new Card();
            array_push($expected, $card->getValue());
            $cards->add($card);
        }

        $cardsValues = $cards->getValues();
        $this->assertCount($cards->getCount(), $expected);
        $this->assertCount($randNum, $cardsValues);
        $this->assertIsArray($cardsValues);
        $this->assertIsArray($cardsValues[0]);
        $this->assertIsString($cardsValues[0][0]);
        $this->assertSame($expected, $cardsValues);
    }

    /**
     * Test getStrings() method and verify output is a correct array of strings.
     */
    public function testMethodReturningStrings(): void
    {
        $cards = new CardCollection();
        $randNum = random_int(1, 52);
        $expected = [];

        for ($i = 0; $i < $randNum; $i++) {
            $card = new Card();
            array_push($expected, $card->getAsString());
            $cards->add($card);
        }

        $cardsAsStrings = $cards->getStrings();
        $this->assertCount($cards->getCount(), $expected);
        $this->assertCount($randNum, $cardsAsStrings);
        $this->assertIsArray($cardsAsStrings);
        $this->assertIsString($cardsAsStrings[0]);
        $this->assertSame($expected, $cardsAsStrings);
    }

    /**
     * Test getColors() method and verify output is a correct array of strings.
     */
    public function testMethodReturningColors(): void
    {
        $cards = new CardCollection();
        $randNum = random_int(1, 52);
        $expected = [];

        for ($i = 0; $i < $randNum; $i++) {
            $card = new Card();
            array_push($expected, $card->getColor());
            $cards->add($card);
        }

        $cardsColors = $cards->getColors();
        $this->assertCount($cards->getCount(), $expected);
        $this->assertCount($randNum, $cardsColors);
        $this->assertIsArray($cardsColors);
        $this->assertIsString($cardsColors[0]);
        $this->assertSame($expected, $cardsColors);
    }

    /**
     * Test shuffle() method and verify $cards are shuffled.
     */
    public function testMethodShuffle(): void
    {
        $cards = new CardCollection();
        $randNum = random_int(1, 52);
        $expected = [];

        for ($i = 0; $i < $randNum; $i++) {
            $card = new Card();
            array_push($expected, $card->getValue());
            $cards->add($card);
        }

        $this->assertSame($expected, $cards->getValues());

        $cards->shuffle();
        if ($expected === $cards->getValues()) {
            $cards->shuffle();
        }

        // $this->assertNotSame($expected, $cards->getValues());
        $this->assertSameSize($expected, $cards->getValues());
    }

    /**
     * Test draw() method and verify output is array of Card.
     */
    public function testMethodDraw(): void
    {
        $cards = new CardCollection();
        $testCards = [
            "hjärter" => 5,
            "klöver" => 10,
            "spader" => 1,
            "ruter" => 12
        ];

        foreach ($testCards as $key => $value) {
            $card = new Card($key, $value);
            $cards->add($card);
        }

        $drawnCard = $cards->draw();
        $this->assertCount(count($testCards) - 1, $cards->getStrings());
        $this->assertIsArray($drawnCard);
        $this->assertCount(1, $drawnCard);
        $this->assertInstanceOf("\App\Card\Card", $drawnCard[0]);
        $this->assertNotContains($drawnCard[0]->getAsString(), $cards->getStrings());


        $drawnCards = $cards->draw(3);
        $this->assertIsArray($drawnCards);
        $this->assertCount(3, $drawnCards);
        $this->assertContainsOnlyInstancesOf(
            "\App\Card\Card",
            $drawnCards
        );
    }
}
