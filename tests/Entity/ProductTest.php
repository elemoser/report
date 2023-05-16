<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Entity class Product.
 */
class ProductTest extends TestCase
{
    /**
     * @var Product $product
     */
    protected $product;

    /**
     * Set up for tests.
     */
    public function setUp(): void
    {
        $this->product = new Product();
    }

    /**
     * Construct object and verify the type.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\Entity\Product", $this->product);
        $this->assertNull($this->product->getId());
    }

    /**
     * Set product name and test getter method.
     */
    public function testGetAndSetName(): void
    {
        $name = "dish washer";
        $this->product->setName($name);
        $this->assertEquals($name, $this->product->getName());
        $this->assertIsString($this->product->getName());
    }

    /**
     * Set product value and test getter method.
     */
    public function testGetAndSetValue(): void
    {
        $value = random_int(0, 99);

        $this->product->setValue($value);
        $this->assertEquals($value, $this->product->getValue());
        $this->assertIsInt($this->product->getValue());
    }
}
