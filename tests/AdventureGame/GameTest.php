<?php

namespace App\AdventureGame;

use App\Entity\AdventureRoom;
use App\Entity\AdventureItems;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class AdventureGame\Game.
 */
class GameTest extends TestCase
{
    /**
     * @var Game $game
     */
    protected $game;

    /**
     * @var AdventureRoom $room
     */
    protected $room;

    /**
     * @var AdventureItems $item
     */
    protected $item;

    /**
     * Set up for tests.
     */
    public function setUp(): void
    {
        $this->room = (new AdventureRoom())
            ->setName("house")
            ->setDescription("a house in the Sweden")
            ->setImage("sunflower.jpg")
            ->setNorth("garden")
            ->setEast("null")
            ->setSouth("garage")
            ->setWest("forest")
            ->setInspect("The house contains many mysteries.")
        ;

        $this->item = (new AdventureItems())
            ->setName("house key")
            ->setDescription("key to the house")
            ->setPrice(0)
            ->setImage("key.svg")
            ->setRoom("house")
        ;

        $this->game = new Game($this->room, [$this->item]);
    }

    /**
     * Construct object with arguments and verify the default values are set correctly.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\AdventureGame\Game", $this->game);
    }

    /**
     * Test method getCurrentRoom().
     */
    public function testGetCurrentRoom(): void
    {
        $room = $this->game->getCurrentRoom();

        $this->assertInstanceOf("App\Entity\AdventureRoom", $room);
        $this->assertEquals($this->game->getCurrentRoom(), $this->room);
    }

    /**
     * Test method getCurrentRoomItems().
     */
    public function testGetCurrentRoomItems(): void
    {
        $item = $this->game->getCurrentRoomItems();

        $this->assertNotInstanceOf("App\Entity\AdventureItems", $item);
        $this->assertIsArray($item);
        $this->assertContainsOnly("string", $item);
    }

    /**
     * Test method setRoomTo().
     */
    public function testSetRoomTo(): void
    {
        $newRoom = new AdventureRoom();
        $newItem = new AdventureItems();

        $this->game->setRoomTo($newRoom, [$newItem]);

        $this->assertNotEquals($this->game->getCurrentRoom(), $this->room);
    }

    /**
     * Test method getVisitedRooms().
     */
    public function testGetVisitedRooms(): void
    {
        $this->assertIsArray($this->game->getVisitedRooms());
        $this->assertNotEmpty($this->game->getVisitedRooms());
        $this->assertContains($this->room->getName(), $this->game->getVisitedRooms());
        $this->assertCount(1, $this->game->getVisitedRooms());


        $newRoom = new AdventureRoom();
        $newItem = new AdventureItems();

        $this->game->setRoomTo($newRoom, [$newItem]);

        $this->assertIsArray($this->game->getVisitedRooms());
        $this->assertNotEmpty($this->game->getVisitedRooms());
        $this->assertCount(2, $this->game->getVisitedRooms());
    }

    /**
     * Test method getDirections().
     */
    public function testGetDirections(): void
    {
        $directions = $this->game->getDirections();

        $this->assertIsArray($directions);
        $this->assertCount(3, $directions);
        $this->assertNotContains("east", $directions);
        $this->assertContains("north", $directions);

        $newRoom = (new AdventureRoom())
            ->setName("house")
            ->setDescription("a house in the Sweden")
            ->setImage("sunflower.jpg")
            ->setNorth("garden")
            ->setEast("entrance")
            ->setSouth("garage")
            ->setWest("forest")
            ->setInspect("The house contains many mysteries.")
        ;
        $this->game->setRoomTo($newRoom, [$this->item]);
        $this->assertContains("east", $this->game->getDirections());
    }

    /**
     * Test method checkValidDirection().
     */
    public function testCheckValidDirection(): void
    {
        $this->assertTrue($this->game->checkValidDirection("south"));
        $this->assertFalse($this->game->checkValidDirection("east"));

        $newRoom = (new AdventureRoom())
            ->setName("house")
            ->setDescription("a house in the Sweden")
            ->setImage("sunflower.jpg")
            ->setNorth("garden")
            ->setEast("entrance")
            ->setSouth("garage")
            ->setWest("forest")
            ->setInspect("The house contains many mysteries.")
        ;
        $this->game->setRoomTo($newRoom, [$this->item]);
        $this->assertTrue($this->game->checkValidDirection("east"));
    }

    /**
     * Test method getDirectionsAsString().
     */
    public function testGetDirectionsAsString(): void
    {
        $directionAsString = $this->game->getDirectionsAsString();

        $this->assertStringContainsString("north", $directionAsString);
        $this->assertStringContainsString("south", $directionAsString);
        $this->assertStringContainsString("west", $directionAsString);
        $this->assertStringNotContainsString("east", $directionAsString);
    }

    /**
     * Test method getLocationOfDirection().
     */
    public function testGetLocationOfDirection(): void
    {
        $this->assertEquals($this->room->getNorth(), $this->game->getLocationOfDirection("north"));
        $this->assertEquals($this->room->getSouth(), $this->game->getLocationOfDirection("south"));
        $this->assertEquals($this->room->getWest(), $this->game->getLocationOfDirection("west"));
        $this->assertEmpty($this->game->getLocationOfDirection("east"));
    }

    /**
     * Test method getBasket() when basket is empty.
     */
    public function testGetBasketEmpty(): void
    {
        $this->assertEmpty($this->game->getBasket());
    }

    /**
     * Test method pickUp().
     */
    public function testPickUpOne(): void
    {
        $output = $this->game->pickUp($this->item);

        $this->assertIsString($output);
        $this->assertStringContainsString("key", $output);
    }

    /**
     * Test method pickUp().
     */
    public function testPickUpMany(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $item = (new AdventureItems())
                ->setName(strval($i))
            ;
            $this->game->pickUp($item);
        }

        $output = $this->game->pickUp($this->item);
        $this->assertIsString($output);
        $this->assertStringContainsString("full", $output);
    }

    /**
     * Test method putDown().
     */
    public function testPutDownEmpty(): void
    {
        $output = $this->game->putDown($this->item);

        $this->assertIsString($output);
        $this->assertStringContainsString("empty", $output);
    }

    /**
     * Test method putDown().
     */
    public function testPutDownNotExist(): void
    {
        $someItem = (new AdventureItems())->setName("doormat");
        $this->game->pickUp($someItem);
        $output = $this->game->putDown($this->item);

        $this->assertIsString($output);
        $this->assertStringContainsString("not in the basket", $output);
    }

    /**
     * Test method putDown().
     */
    public function testPutDownExist(): void
    {
        $someItem = (new AdventureItems())->setName("doormat");
        $this->game->pickUp($someItem);
        $output = $this->game->putDown($someItem);

        $this->assertIsString($output);
        $this->assertStringContainsString("doormat", $output);
    }

    /**
     * Test method checkItemInBasket().
     */
    public function testCheckItemInBasket(): void
    {
        $this->assertFalse($this->game->checkItemInBasket("key"));

        $output = $this->game->pickUp($this->item);
        $itemName = $this->item->getName();
        if ($itemName) {
            $this->assertStringContainsString($itemName, $output);
            $this->assertTrue($this->game->checkItemInBasket($itemName));
        }
    }

    /**
     * Test method checkIngredients().
     */
    public function testCheckIngredients(): void
    {
        $ingredients = [
            "salt",
            "sugar",
            "jam",
            "vanilla",
            "flour",
            "strawberries",
            "cream",
            "milk",
            "eggs",
            "butter"
        ];

        // basket is empty
        $this->assertFalse($this->game->checkIngredients());

        // basket contains ingredients but not all
        $salt = (new AdventureItems())->setName($ingredients[0]);
        $this->game->pickUp($salt);
        $this->assertFalse($this->game->checkIngredients());

        foreach ($ingredients as $ingredient) {
            $item = (new AdventureItems())->setName($ingredient);
            $this->game->pickUp($item);
        }
        $this->assertTrue($this->game->checkIngredients());
    }

    /**
     * Test method inspect().
     */
    public function testInspectRoom(): void
    {
        $output = $this->game->inspect("house");

        $this->assertNotEmpty($output);
        $this->assertIsString($output);
        $this->assertStringContainsString("house", $output);
    }

    /**
     * Test method inspect().
     */
    public function testInspectItem(): void
    {
        $output = $this->game->inspect("house key");

        $this->assertNotEmpty($output);
        $this->assertIsString($output);
        $this->assertStringContainsString("key", $output);
    }

    /**
     * Test method inspect().
     */
    public function testInspectBasketItem(): void
    {
        $newItem = (new AdventureItems())->setName("doormat")->setDescription("dirty doormat");
        $this->game->pickUp($newItem);
        $output = $this->game->inspect("doormat");

        $this->assertNotEmpty($output);
        $this->assertIsString($output);
        $this->assertStringContainsString("doormat", $output);
    }

    /**
     * Test method inspect().
     */
    public function testInspectEmptyInput(): void
    {
        $output = $this->game->inspect("");

        $this->assertNotEmpty($output);
        $this->assertIsString($output);
        $this->assertStringContainsString("inspect", $output);
    }

    /**
     * Test method getActions().
     */
    public function testGetActions(): void
    {
        $actions = $this->game->getActions();

        $this->assertNotEmpty($actions);
        $this->assertContains("bake", $actions);
        $this->assertContains("pick up", $actions);
        $this->assertContains("put back", $actions);
        $this->assertContains("inspect", $actions);
        $this->assertContains("go", $actions);
    }
}
