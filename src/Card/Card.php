<?php

namespace App\Card;

class Card
{
    /**
     * @var array<string> $value
     */
    protected $value;

    /**
     * @var array<string> $suites
     */
    public $suites;

    /**
     * @var int $minValue
     */
    public $minValue;

    /**
     * @var int $maxValue
     */
    public $maxValue;

    public function __construct(
        string $suite = null,
        int $number = null
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

        $number = $this->convertCardNumberToString($number);

        $this->value = [$suite, $number];
    }

    /**
     * @return string
     */
    protected function convertCardNumberToString(int $integer)
    {
        $number = (string) $integer;

        if ($number == "1") {
            $number = "ess";
        }

        if ($number == "11") {
            $number = "knekt";
        }

        if ($number == "12") {
            $number = "dam";
        }

        if ($number == "13") {
            $number = "kung";
        }

        return $number;
    }

    /**
     * @return array<string>
     */
    public function draw()
    {
        // Generate a new random suite and number
        $randSuites = array_rand($this->suites, 1);
        $number = random_int($this->minValue, $this->maxValue);

        $number = $this->convertCardNumberToString($number);

        // $this->value = ["klöver", "ess"];
        $this->value = [$this->suites[$randSuites], $number];

        return $this->value;
    }

    /**
     * @return array<string>
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getAsString()
    {
        return join(" ", $this->value);
    }

    /**
     * @return string
     */
    public function getColor()
    {
        $color = "black";

        if (in_array("ruter", $this->value) or in_array("hjärter", $this->value)) {
            $color = "red";
        }

        return $color;
    }
}
