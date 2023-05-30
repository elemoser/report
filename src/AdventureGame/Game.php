<?php

namespace App\AdventureGame;

use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
// use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\ORM\EntityRepository;

class Game
{
    /**
     * @var AdventureRoom $currentRoom
     */
    protected $currentRoom;

    /**
     * @var array<int,AdventureItems> $currentItems
     */
    protected $currentItems;

    /**
     * @var array<int,string> $basket
     */
    protected $basket;

    /**
     * @var array<int,string> $visited
     */
    protected $visited;

    // private $entityManager;
    // private $repository;

    /**
     * The constructor.
     * @return void
     */
    public function __construct(AdventureRoom $startLocation, array $items)
    // public function __construct(EntityManagerInterface $entityManager, EntityRepository $repository)
    {
        $this->currentRoom = $startLocation;
        $this->currentItems = $items;
        $this->basket = [];
        $this->visited = [];

        // initiate game by loading all the data from csv in database
        // $this->entityManager = $entityManager;
        // $this->repository = $repository;
    }

    /**
     * This method ...
     * @return void
     */
    public function initiate()
    {
        // get information about the new place and return?
        // update $currentRoom and $visited
    }

    /**
     * This method returns the current location as object.
     * @return object
     */
    public function getCurrentRoom()
    {
        return $this->currentRoom;
    }

    /**
     * This method returns all items in the room as an array of strings.
     * @return array<int,string>
     */
    public function getCurrentRoomItems()
    {
        $items = [];

        if (count($this->currentItems) > 0) {
            foreach ($this->currentItems as $item) {
                array_push($items, $item->getName());
            }
        }

        return $items;
    }

    /**
     * This method updates the internal game values.
     * @return void
     */
    public function setRoomTo(AdventureRoom $newLocation, array $items)
    {
        $newLocationName = $newLocation->getName();
        if ($newLocationName != $this->currentRoom->getName()) {
            $this->currentRoom = $newLocation;
            $this->currentItems = $items;
        }

        if (!in_array($newLocationName, $this->visited)) {
            array_push($this->visited, $newLocationName);
        }
    }

    /**
     * This method returns an array containing all the locations visited.
     * @return array<int, string>
     */
    public function getVisitedRooms()
    {
        return $this->visited;
    }

    /**
     * This method returns an array containing all the possible direction from location.
     * @return string
     */
    public function getDirectionsAsString()
    {
        $message = "From here you can go ";
        $directions = [];

        if ($this->currentRoom->getNorth() != "null") {
            $directions[] = "north to the ".$this->currentRoom->getNorth();
        }

        if ($this->currentRoom->getEast() != "null") {
            $directions[] = "east to the ".$this->currentRoom->getEast();
        }

        if ($this->currentRoom->getSouth() != "null") {
            $directions[] = "south to the ".$this->currentRoom->getSouth();
        }

        if ($this->currentRoom->getWest() != "null") {
            $directions[] = "west to the ".$this->currentRoom->getWest();
        }

        $message .= $directions[0];

        // add other items to $message separated by a coma
        if (count($directions) > 1) {
            array_splice($directions, 0, 1);
            $message .= ", or ";
            $message .= join(", or ", $directions);
        }

        return $message.".";
    }

    /**
     * This method returns an array with the possible directions.
     * @return array<int, string>
     */
    public function getDirections()
    {
        $directions = [];

        if ($this->currentRoom->getNorth() != "null") {
            $directions[] = "north";
        }

        if ($this->currentRoom->getEast() != "null") {
            $directions[] = "east";
        }

        if ($this->currentRoom->getSouth() != "null") {
            $directions[] = "south";
        }

        if ($this->currentRoom->getWest() != "null") {
            $directions[] = "west";
        }

        return $directions;
    }

    /**
     * This method returns true if the input is a valid direction.
     * @return boolean
     */
    public function checkValidDirection(string $input)
    {
        $valid = False;
        $directions = $this->getDirections();

        if (in_array($input, $directions)) {
            $valid = True;
        }

        return $valid;
    }

    /**
     * This method returns the name of the place in the given direction.
     * @return boolean
     */
    public function getLocationOfDirection(string $input)
    {
        $result = $this->checkValidDirection($input);

        $location = "";

        if ($input == "north") {
            $location = $this->currentRoom->getNorth();
        }

        if ($input == "east") {
            $location = $this->currentRoom->getEast();
        }

        if ($input == "south") {
            $location = $this->currentRoom->getSouth();
        }

        if ($input == "west") {
            $location = $this->currentRoom->getWest();
        }

        if (!$result) {
            $location = "";
        }

        return $location;
    }

    /**
     * This method returns all actions allowed.
     * @return array<int, string>
     */
    public function getActions()
    {
        $roomName = $this->currentRoom->getName();
        $actions = [
            "inspect" => $roomName." or object",
            "pick up" => "object",
            "put back" => "object",
        ];
        
        $directions = $this->getDirections();

        $actions["go"] = join(" or ", $directions);

        return $actions;
    }

    /**
     * This method checks if the basket contains the ingredients for the cake.
     * @return boolean
     */
    public function checkIngredients()
    {
        $complete = true;
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

        foreach ($this->basket as $item) {
            if (!in_array($item, $ingredients)) {
                $complete = false;
            }
        }

        return $complete;
    }

    /**
     * This method returns the text when player "inspects" the current room.
     * @return string
     */
    public function inspect(string $object)
    {
        $result = "You cannot inspect '".$object."'. Try something else.";

        if ($object == $this->currentRoom->getName()) {
            $result = "You look around... ";
            $result .= $this->currentRoom->getInspect();
        }

        $items = $this->getCurrentRoomItems();

        if (in_array($object, $items)) {
            foreach ($this->currentItems as $item) {
                if ($item->getName() == $object) {
                    $result = "You see ";
                    $result .= $item->getDescription().".";
                }
            }
        }

        return $result;
    }

    /**
     * This method adds the given item to the basket.
     * @return string
     */
    public function pickUp(string $item)
    {
        $message = "The basket is full.";

        if (count($this->basket) < 10) {
            $message = "You have picked up the ".$item.".";

            if (!in_array($item, $this->basket)) {
                array_push($this->basket, $item);
                $message = "You already have picked up ".$item.".";
            }
        }

        return $message;
    }

    /**
     * This method removes the given item from the basket.
     * @return string
     */
    public function putDown(string $item)
    {
        $message = "The basket is empty.";

        if (count($this->basket) > 0) {
            $message = "The ".$item." are not in the basket.";

            if (in_array($item, $this->basket)) {
                $key = array_search($item, $this->basket);
                unset($this->basket[$key]);
                $message = "You put down the ".$item.".";
            }
        }

        return $message;
    }

    /**
     * This method return the basket as an array.
     * @return array<int, string>
     */
    public function getBasket()
    {
        return $this->basket;
    }
}
