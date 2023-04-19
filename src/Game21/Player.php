<?php

namespace App\Game21;

use App\Card\Card;
use App\Card\CardCollection;

class Player
{
    /**
     * @var CardCollection $cardHand
     */
    protected $cardHand;

    public function __construct()
    {
        $this->cardHand = new CardCollection;
    }

    public function addCard(Card $newCard): void
    {
        $this->cardHand->add($newCard);
    }

    // public function removeCard(): void

    /**
     * @return array<string>
     */
    public function getHandAsString()
    {
        // return all the cards in the current hand
        // as strings, if possible with their respective color
        return $this->cardHand->getStrings();
    }

    /**
     * @return int
     */
    public function getHandValues()
    {
        // return the current sum of all cards
        // in the cardHand
        
        // for card in cardHand get card->getValues()
        // append to $cardHandValues
    }
}