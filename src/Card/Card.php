<?php

namespace App\Card;

class Card
{
    protected $value;

    public function __construct()
    {
        $this->value = null;
    }

    public function draw(): array
    {
        $number = random_int(1, 13);
        $suite = ["klöver", "ruter", "hjärter", "spader"];
        $randSuite = array_rand($suite, 1);
        
        if ($number == 1) {
            $number = "ess";
        }

        if ($number == 11) {
            $number = "knekt";
        }

        if ($number == 12) {
            $number = "dam";
        }

        if ($number == 13) {
            $number = "kung";
        }

        $this->value = [$suite[$randSuite], $number];
        // $this->value = ["klöver", "ess"];

        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return join(" ", $this->value);
    }
}