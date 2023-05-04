<?php

namespace App\Card;

use App\Card\Card;

class CardCollection
{
    /**
     * @var array<Card> $cards
     */
    protected $cards;

    /**
     * The constructor takes to no parameters.
     * @return void
     */
    public function __construct()
    {
        $this->cards = [];
    }

    /**
     * This methods adds a given Card instance to the $card property.
     */
    public function add(Card $card): void
    {
        array_push($this->cards, $card);
    }

    /**
     * This methods removes a given Card instance from the $card property.
     */
    public function remove(Card $card): void
    {
        if (in_array($card, $this->cards)) {
            $index = array_search($card, $this->cards);
            unset($this->cards[$index]);
            // Reindex array?
            // $this->cards = array_values($this->cards);
        }
    }

    /**
     * This methods changes the order of the elements in the $card property.
     */
    public function shuffle(): void
    {
        // Change the order of the cards array
        shuffle($this->cards);
    }

    /**
     * This method returns the total amount of elements in the property $cards.
     * @return int
     */
    public function getCount()
    {
        return count($this->cards);
    }

    /**
     * This method returns an array containing the suite and number of each Card in the $cards property.
     * @return array<int, array<string>>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /**
     * This method returns an array containing the suite and number as a string of each Card in the $cards property.
     * @return array<string>
     */
    public function getStrings(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    /**
     * This method returns an array containing the color of each Card in the $cards property.
     * @return array<string>
     */
    public function getColors(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->getColor();
        }
        return $values;
    }

    /**
     * This method removes a random Card from the $cards property and returns it.
     * @return array<Card>
     */
    public function draw(int $number = 1): array
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
