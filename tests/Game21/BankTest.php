<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Bank.
 */
class BankTest extends TestCase
{
    /**
     * Construct object and verify the default values are set correctly.
     */
    public function testCreateObject(): void
    {
        $bank = new Bank();
        $this->assertInstanceOf("\App\Game21\Player", $bank);
        $this->assertInstanceOf("\App\Game21\Bank", $bank);
        $this->assertEquals("bank", $bank->name);
    }
}
