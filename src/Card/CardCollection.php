<?php

namespace App\Card;

use App\Card\Card;

class CardCollection {
    protected $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function add(Card $card): void
    {
        array_push($this->cards, $card);
    }

    public function remove(Card $card): void
    {
        // Find the index of the Card object
        $index = array_search($card, $this->cards);
        // Remove the Card object
        if ($index) {
            unset($this->cards[$index]);
            // Reindex array?
            // $this->cards = array_values($this->cards);
        }
    }

    public function shuffle(): void
    {
        // Change the order of the cards array
        shuffle($this->cards);
    }

    public function getCount(): int
    {
        return count($this->cards);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    public function getStrings(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    public function getColors(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getColor();
        }
        return $values;
    }

    public function draw($number = 1): array
    {
        $randCards = [];
        $randIndexes = array_rand($this->cards, $number);

        if (is_array($randIndexes)) {
            foreach ($randIndexes as $idx) {
                array_push($randCards, $this->cards[$idx]);
                unset($this->cards[$idx]);
            }
        }

        if (is_int($randIndexes)) {
            array_push($randCards, $this->cards[$randIndexes]);
                unset($this->cards[$randIndexes]);
        }


        return $randCards;
    }
}