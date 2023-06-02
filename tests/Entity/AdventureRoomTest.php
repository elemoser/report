<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Entity class AdventureRoom.
 */
class AdventureRoomTest extends TestCase
{
    /**
     * @var AdventureRoom $room
     */
    protected $room;

    /**
     * Set up for tests.
     */
    public function setUp(): void
    {
        $this->room = new AdventureRoom();
    }

    /**
     * Construct object and verify the type.
     */
    public function testCreateObject(): void
    {
        $this->assertInstanceOf("\App\Entity\AdventureRoom", $this->room);
        $this->assertNull($this->room->getId());
    }

    /**
     * Set room name and test getter method.
     */
    public function testGetAndSetName(): void
    {
        $this->assertNull($this->room->getName());

        $this->room->setName("Hallway");
        $this->assertEquals("Hallway", $this->room->getName());
        $this->assertIsString($this->room->getName());
    }

    /**
     * Set room description and test getter method.
     */
    public function testGetAndSetDescription(): void
    {
        $this->assertNull($this->room->getDescription());

        $this->room->setDescription("
            The hallway of a Swedish country house embodies a harmonious blend of rustic charm and traditional design.
            Its walls feature light-colored wooden paneling, creating a cozy and inviting atmosphere.
            Polished hardwood or stone tile flooring adds a touch of natural beauty, while vibrant patterned rugs infuse color and comfort.
            Large windows allow ample natural light to fill the space, offering captivating views of the picturesque countryside.
            Practical elements like wooden hooks for hanging coats and a shoe rack add functionality, while traditional Swedish folk art adorns the walls, showcasing the country's cultural heritage.
            Soft, ambient lighting fixtures in materials like brass or wrought iron illuminate the hallway, creating a warm and intimate ambiance.
            The hallway reflects the simplicity and appreciation for nature's beauty that is inherent in Swedish design.
            It serves as a welcoming space, drawing visitors into the heart of the country house, with its emphasis on craftsmanship, traditional elements, and a connection to the surrounding natural environment.
        ");
        $this->assertIsString($this->room->getDescription());
        $this->assertStringContainsString("hallway", $this->room->getDescription());
    }

    /**
     * Set room image and test getter method.
     */
    public function testGetAndSetImage(): void
    {
        $this->assertNull($this->room->getImage());

        $this->room->setImage("a_swedish_hallway.jpg");
        $this->assertIsString($this->room->getImage());
    }

    /**
     * Set room direction "north" and test getter method.
     */
    public function testGetAndSetNorth(): void
    {
        $this->assertNull($this->room->getNorth());

        $this->room->setNorth("bathroom");
        $this->assertIsString($this->room->getNorth());
        $this->assertEquals("bathroom", $this->room->getNorth());

    }

    /**
     * Set room direction "west" and test getter method.
     */
    public function testGetAndSetWest(): void
    {
        $this->assertNull($this->room->getWest());

        $this->room->setWest("something");
        $this->assertIsString($this->room->getWest());
        $this->assertEquals("something", $this->room->getWest());
    }

    /**
     * Set room direction "east" and test getter method.
     */
    public function testGetAndSetEast(): void
    {
        $this->assertNull($this->room->getEast());

        $this->room->setEast("kitchen");
        $this->assertIsString($this->room->getEast());
        $this->assertEquals("kitchen", $this->room->getEast());
    }

    /**
     * Set room direction "south" and test getter method.
     */
    public function testGetAndSetSouth(): void
    {
        $this->assertNull($this->room->getSouth());

        $this->room->setSouth("stairs");
        $this->assertIsString($this->room->getSouth());
        $this->assertEquals("stairs", $this->room->getSouth());
    }


    /**
     * Set room inspection text and test getter method.
     */
    public function testGetAndSetInspect(): void
    {
        $this->assertNull($this->room->getInspect());

        $this->room->setInspect("A small hallway.");
        $this->assertIsString($this->room->getInspect());
        $this->assertStringContainsString("hallway", $this->room->getInspect());
    }
}
