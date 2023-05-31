<?php

namespace App\AdventureGame;

use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;

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
     * @var array<int,AdventureItems> $basket
     */
    protected $basket;

    /**
     * @var array<int,string> $visited
     */
    protected $visited;

    /**
     * The constructor.
     * @param AdventureRoom $startLocation
     * @param array<int,AdventureItems> $items
     * @return void
     */
    public function __construct($startLocation, $items)
    {
        $this->currentRoom = $startLocation;
        $this->currentItems = $items;
        $this->basket = [];
        $this->visited = [];
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
     * @return array<int,string|null>
     */
    public function getCurrentRoomItems()
    {
        $items = [];

        if (!empty($this->currentItems)) {
            foreach ($this->currentItems as $item) {
                array_push($items, $item->getName());
            }
        }

        return $items;
    }

    /**
     * This method updates the internal game values.
     * @param AdventureRoom $newLocation
     * @param array<int,AdventureItems> $items
     * @return void
     */
    public function setRoomTo($newLocation, $items)
    {
        $newLocationName = $newLocation->getName();
        // if ($newLocationName != $this->currentRoom->getName()) {
        //     $this->currentRoom = $newLocation;
        //     $this->currentItems = $items;
        // }

        $this->currentRoom = $newLocation;
        $this->currentItems = $items;

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

        $directions = $this->getDirections();
        $strings = [];

        foreach ($directions as $direction) {
            $locationName = $this->getLocationOfDirection($direction);
            array_push($strings, $direction." to the ".$locationName);
        }

        $message .= $strings[0];

        // add other items to $message separated by a coma
        if (count($strings) > 1) {
            array_splice($strings, 0, 1);
            $message .= ", or ";
            $message .= join(", or ", $strings);
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
        $valid = false;
        $directions = $this->getDirections();

        if (in_array($input, $directions)) {
            $valid = true;
        }

        return $valid;
    }

    /**
     * This method returns the name of the place in the given direction.
     * @return string|null
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
     * @return array<string, string>
     */
    public function getActions()
    {
        // REFACTOR!
        $roomName = $this->currentRoom->getName();
        $actions = [
            "inspect" => $roomName." or object",
            "pick up" => "object",
            "put back" => "object",
        ];

        $directions = $this->getDirections();

        $actions["go"] = join(" or ", $directions);
        $actions["bake"] = "";

        return $actions;
    }

    /**
     * This method checks if the basket contains the ingredients for the cake.
     * @return boolean
     */
    public function checkIngredients()
    {
        $complete = false;
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

        $count = count($ingredients);

        foreach ($this->basket as $item) {
            if (in_array($item->getName(), $ingredients)) {
                $count -= 1;
            }
        }

        if ($count == 0) {
            $complete = true;
        }

        return $complete;
    }

    /**
     * This method returns the text when player "inspects" a room or an object.
     * @return string
     */
    public function inspect(string $object)
    {
        $result = "You cannot inspect '".$object."'. Try something else.";

        if (empty($object)) {
            $result = "You need to specify what you want to inspect. ";
            $result .= "For example, 'inspect ".$this->currentRoom->getName()."'";
        }

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

        $itemIsInBasket = $this->checkItemInBasket($object);

        if ($itemIsInBasket) {
            foreach ($this->basket as $item) {
                if ($item->getName() == $object) {
                    $result = "In your basket, you have ";
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
    public function pickUp(AdventureItems $item)
    {
        $message = "The basket is full.";

        if (count($this->basket) < 10) {
            $itemName = $item->getName();
            $message = "You already have picked up ".$itemName.".";

            if (!in_array($item, $this->basket)) {
                array_push($this->basket, $item);
                $message = "You have picked up the ".$itemName.".";
            }
        }

        return $message;
    }

    /**
     * This method removes the given item from the basket.
     * @return string
     */
    public function putDown(AdventureItems $item)
    {
        $message = "The basket is empty.";

        if (count($this->basket) > 0) {
            $itemName = $item->getName();
            $message = "The ".$itemName." are not in the basket.";

            if (in_array($item, $this->basket)) {
                $key = array_search($item, $this->basket);
                unset($this->basket[$key]);
                $message = "You put down the ".$itemName.".";
            }
        }

        return $message;
    }

    /**
     * This method return the basket as an array.
     * @return array<int, object>
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * This method returns True if given item is in basket.
     * @return boolean
     */
    public function checkItemInBasket(string $input)
    {
        $valid = false;

        if (count($this->basket) > 0) {
            foreach ($this->basket as $item) {
                $itemName = $item->getName();
                if ($itemName == $input) {
                    $valid = true;
                }
            }
        }

        return $valid;
    }
}
