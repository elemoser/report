<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object without arguments and verify the default values are set correctly.
     */
    public function testCreateObjectNoArguments(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertEquals($card->minValue, 1);
        $this->assertEquals($card->maxValue, 13);
        $this->assertContains("ruter", $card->suites);
        $this->assertContains("klöver", $card->suites);
        $this->assertContains("hjärter", $card->suites);
        $this->assertContains("spader", $card->suites);
    }

    /**
     * Construct object with arguments and verify the default values are set correctly.
     */
    public function testCreateObjectWithArguments(): void
    {
        $suites = ["hjärter", "spader", "ruter", "klöver"];
        $randSuite = $suites[array_rand($suites, 1)];
        $randNumber = random_int(1, 13);
        $card = new Card($randSuite, $randNumber);
        $this->assertIsString($randSuite);
        $this->assertIsInt($randNumber);
        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertEquals($card->minValue, 1);
        $this->assertEquals($card->maxValue, 13);
        $this->assertContains("ruter", $card->suites);
        $this->assertContains("klöver", $card->suites);
        $this->assertContains("hjärter", $card->suites);
        $this->assertContains("spader", $card->suites);
    }

    /**
     * Test getValue() method and verify the output is an array of strings.
     */
    public function testMethodReturningCardValue(): void
    {
        $suites = ["hjärter", "spader", "ruter", "klöver"];
        $randSuite = $suites[array_rand($suites, 1)];
        $randNumber = random_int(1, 13);
        $card = new Card($randSuite, $randNumber);
        $value = $card->getValue();

        $cardValue = strval($randNumber);

        if ($cardValue == "1") {
            $cardValue = "ess";
        }

        if ($cardValue == "11") {
            $cardValue = "knekt";
        }

        if ($cardValue == "12") {
            $cardValue = "dam";
        }

        if ($cardValue == "13") {
            $cardValue = "kung";
        }

        $this->assertIsArray($value);
        $this->assertCount(2, $value);
        $this->assertContainsOnly('string', $value);
        $this->assertContains($randSuite, $value);
        $this->assertNotContains($randNumber, $value);
        $this->assertContains($cardValue, $value);
    }

    /**
     * Test getAsString() method and verify the output is a string.
     */
    public function testMethodReturningCardValuesAsString(): void
    {
        $suites = ["hjärter", "spader", "ruter", "klöver"];
        $randSuite = $suites[array_rand($suites, 1)];
        $randNumber = random_int(1, 13);
        $card = new Card($randSuite, $randNumber);
        $output = $card->getAsString();

        $this->assertIsString($output);
        $this->assertStringContainsString($randSuite, $output);
    }

    /**
     * Test getColor() method and verify the output is a string.
     */
    public function testMethodReturningCardColor(): void
    {
        $suites = ["hjärter", "spader", "ruter", "klöver"];
        $randSuite = $suites[array_rand($suites, 1)];
        $randNumber = random_int(1, 13);
        $card = new Card($randSuite, $randNumber);
        $output = $card->getColor();

        $expectedColor = "black";
        if ($randSuite === "hjärter" || $randSuite === "ruter") {
            $expectedColor = "red";
        }

        $this->assertIsString($output);
        $this->assertEquals($expectedColor, $output);
    }

    /**
     * Test draw() method and verify the output is correct.
     */
    public function testMethodDraw(): void
    {
        $suites = ["hjärter", "spader", "ruter", "klöver"];
        $randSuite = $suites[array_rand($suites, 1)];
        $randNumber = random_int(1, 13);
        $card = new Card($randSuite, $randNumber);
        // $beforeDraw = $card->getValue();
        $afterDraw = $card->draw();

        $this->assertIsArray($afterDraw);
        // $this->assertNotEquals($beforeDraw, $afterDraw);
    }
}
