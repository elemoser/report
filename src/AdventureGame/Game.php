<?php

namespace App\AdventureGame;

use App\Entity\AdventureRoom;
// use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\ORM\EntityRepository;

class Game
{
    /**
     * @var string $currentRoom
     */
    protected $currentRoom;

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
    public function __construct()
    // public function __construct(EntityManagerInterface $entityManager, EntityRepository $repository)
    {
        $this->currentRoom = "kitchen";
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
     * This method returns the name of the current location.
     * @return string
     */
    public function getCurrentRoom()
    {
        return $this->currentRoom;
    }

    /**
     * This method updates the internal game values.
     * @return void
     */
    public function goTo(string $place)
    {
        if ($place != $this->currentRoom) {
            $this->currentRoom = $place;
        }

        if (in_array($place, $this->visited)) {
            array_push($this->visited, $place);
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
    public function getDirections(AdventureRoom $roomObject)
    {
        $message = "From here you can go ";
        $directions = [];

        if ($roomObject->getNorth() != null) {
            $directions[] = "north to the ".$roomObject->getNorth();
        }

        if ($roomObject->getEast() != null) {
            $directions[] = "east to the ".$roomObject->getEast();
        }

        if ($roomObject->getSouth() != null) {
            $directions[] = "south to the ".$roomObject->getSouth();
        }

        if ($roomObject->getWest() != null) {
            $directions[] = "west to the ".$roomObject->getWest();
        }

        $message += $directions[0];

        // add other items to $message separated by a coma
        // if (count($directions) > 1) {
        //     join(", ", $directions);
        // }


        return $this->visited;
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
     * This method ...
     * @return string
     */
    public function inspect(string $object)
    {
        // returns the description for a place or an item
        return $object;
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
