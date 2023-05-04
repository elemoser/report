<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object without arguments and verify the default values are set correctly.
     */
    public function testCreateObjectNoArguments(): void
    {
        $card = new CardGraphic();
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);
        $this->assertEquals($card->minValue, 1);
        $this->assertEquals($card->maxValue, 13);
        $this->assertContains("ruter", $card->suites);
        $this->assertContains("klöver", $card->suites);
        $this->assertContains("hjärter", $card->suites);
        $this->assertContains("spader", $card->suites);
    }

    /**
     * Construct object without arguments and verify output is a string.
     */
    public function testMethodReturningCardAsString(): void
    {
        $randSuite = "hjärter";
        $randNumber = 9;
        $card = new CardGraphic($randSuite, $randNumber);
        $output = $card->getAsString();
        $this->assertIsString($output);
        $this->assertEquals("127161;", $output);
    }
}
