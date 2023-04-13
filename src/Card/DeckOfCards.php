<?php

namespace App\Card;

use App\Card\CardCollection;

class DeckOfCards extends CardCollection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isCompleteDeck(): bool
    {
        $card = $this->cards[0];
        $completeDeck = ($card->maxValue - $card->minValue + 1) * 4;
        $currentDeck = $this->getCount();

        return $completeDeck === $currentDeck;
    }
}