<?php

namespace App\Card;

class Card
{
    protected $value;
    public $suites;
    public $minValue;
    public $maxValue;

    public function __construct(
        $suite = null,
        $number = null
    ) {
        $this->value = [];
        $this->suites = ["hjärter", "klöver", "ruter", "spader"];
        $this->minValue = 1;
        $this->maxValue = 13;

        if ($suite == null) {
            $randSuites = array_rand($this->suites, 1);
            $suite = $this->suites[$randSuites];
        }

        if ($number == null) {
            $number = random_int($this->minValue, $this->maxValue);
        }

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

        $this->value = [$suite, $number];
    }

    public function draw(): array
    {
        // Generate a new random suite and number
        $randSuites = array_rand($this->suites, 1);
        $number = random_int($this->minValue, $this->maxValue);

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

        // $this->value = ["klöver", "ess"];
        $this->value = [$this->suites[$randSuites], $number];

        return $this->value;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return join(" ", $this->value);
    }

    public function getColor(): string
    {
        $color = "black";

        if (in_array("ruter", $this->value) or in_array("hjärter", $this->value)) {
            $color = "red";
        }

        return $color;
    }
}
