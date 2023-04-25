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

    public function __construct(string $name)
    {
        $this-> name = $name;
        $this->cardHand = new CardCollection();
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
        return $this->cardHand->getStrings();
    }

    /**
     * @return array<int<0, max>, array<string>>
     */
    public function getHandValues()
    {
        return $this->cardHand->getValues();
    }

    /**
     * @return array<string>
     */
    public function getHandColors()
    {
        return $this->cardHand->getColors();
    }

    /**
     * @return int
     */
    public function getHandCount()
    {
        return $this->cardHand->getCount();
    }
}
