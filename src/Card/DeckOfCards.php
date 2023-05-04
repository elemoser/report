<?php

namespace App\Card;

use App\Card\CardCollection;

class DeckOfCards extends CardCollection
{
    /**
     * The constructor takes to no parameters.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method returns true if the elements in $cards from a complete deck of cards.
     */
    public function isCompleteDeck(): bool
    {
        $card = $this->cards[0];
        $completeDeck = ($card->maxValue - $card->minValue + 1) * 4;
        $currentDeck = $this->getCount();

        return $completeDeck === $currentDeck;
    }
}
