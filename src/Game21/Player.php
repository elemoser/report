<?php

namespace App\Game21;

use App\Card\Card;
use App\Card\CardCollection;

class Player
{
    /**
     * @var string $name
     */
    public $name;

    /**
     * @var CardCollection $cardHand
     */
    protected $cardHand;

    /**
     * The constructor takes one parameter.
     * @return void
     */
    public function __construct(string $name)
    {
        $this-> name = $name;
        $this->cardHand = new CardCollection();
    }

    /**
     * This method adds a Card instance to the $cardHand property.
     */
    public function addCard(Card $newCard): void
    {
        $this->cardHand->add($newCard);
    }

    // public function removeCard(): void

    /**
     * This method returns the suite and number as a string of each Card instance in the $cardHand property.
     * @return array<string>
     */
    public function getHandAsString()
    {
        return $this->cardHand->getStrings();
    }

    /**
     * This method returns the suite and number of each Card instance in the $cardHand property.
     * @return array<int, array<string>>
     */
    public function getHandValues()
    {
        return $this->cardHand->getValues();
    }

    /**
     * This method returns the color of each Card instance in the $cardHand property.
     * @return array<string>
     */
    public function getHandColors()
    {
        return $this->cardHand->getColors();
    }

    /**
     * This method returns the total amount of elements in the $cardHand property.
     * @return int
     */
    public function getHandCount()
    {
        return $this->cardHand->getCount();
    }
}
