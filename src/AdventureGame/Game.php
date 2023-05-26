<?php

namespace App\AdventureGame;

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
    public function initiate(){
        // get information about the new place and return?
        // update $currentRoom and $visited
    }

    /**
     * This method ...
     * @return void
     */
    public function go(string $place){
        // get information about the new place and return?
        // update $currentRoom and $visited
    }

        /**
     * This method ...
     * @return boolean
     */
    private function checkIngredients(){
        // check if in kitchen?
        // check if $this->basket includes all required ingredients
    }

    /**
     * This method ...
     * @return void
     */
    public function inspect(string $object){
        // returns the description for a place or an item
    }

    /**
     * This method ...
     * @return void
     */
    public function pickUp(string $item){
        // update $basket
        // return string like "You have picked up ${item}"
        // if $this->basket is full return "Your basket is full"
    }

    /**
     * This method ...
     * @return void
     */
    public function putDown(string $item){
        // update $basket
        // return string "You put back ${item}"
    }
}