<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Entity class AdventureItems.
 */
class AdventureItemsTest extends TestCase
{
    /**
     * @var AdventureItems $item
     */
    protected $item;

    /**
     * Set up for tests.
     */
    public function setUp(): void
    {
        $this->item = new AdventureItems();
    }

    /**
     * Construct object and verify the type.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\Entity\AdventureItems", $this->item);
        $this->assertNull($this->item->getId());
    }

    /**
     * Set item name and test getter method.
     */
    public function testGetAndSetName(): void
    {
        $this->assertNull($this->item->getName());

        $this->item->setName("Cookbook");
        $this->assertEquals("Cookbook", $this->item->getName());
        $this->assertIsString($this->item->getName());
    }

    /**
     * Set item description and test getter method.
     */
    public function testGetAndSetDescription(): void
    {
        $this->assertNull($this->item->getDescription());

        $this->item->setDescription("
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Aliquam vel arcu consectetur, venenatis nisl eget, lobortis sem.
            Ut vitae risus rutrum sem consequat pulvinar eget vitae est.
            Morbi vel vulputate libero.
            Quisque ligula metus, sodales vitae laoreet interdum, ultricies ut ex.
            Integer dui dui, dictum ut risus in, dictum bibendum felis.
            Curabitur luctus libero interdum sollicitudin fringilla. Nullam sed venenatis urna, in fringilla purus.
        ");
        $this->assertIsString($this->item->getDescription());
    }

    /**
     * Set item price and test getter method.
     */
    public function testGetAndSetsPrice(): void
    {
        $this->assertNull($this->item->getPrice());

        $price = random_int(0, 10000);
        $this->item->setPrice($price);
        $this->assertIsInt($this->item->getPrice());
        $this->assertEquals($price, $this->item->getPrice());
    }

    /**
     * Set item image and test getter method.
     */
    public function testGetAndSetsImage(): void
    {
        $this->assertNull($this->item->getImage());

        $this->item->setImage("test_image.jpg");
        $this->assertIsString($this->item->getImage());
    }

    /**
     * Set item room and test getter method.
     */
    public function testGetAndSetsRoom(): void
    {
        $this->assertNull($this->item->getRoom());

        $this->item->setRoom("kitchen");
        $this->assertIsString($this->item->getRoom());
    }
}
