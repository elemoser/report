<?php

namespace App\Card;

class CardGraphic extends Card
{
    /**
     * @var array<string, string> $representation
     */
    private $representation = [
        "klöver ess" => '127185;',
        "klöver 2" => '127186;',
        "klöver 3" => '127187;',
        "klöver 4" => '127188;',
        "klöver 5" => '127189;',
        "klöver 6" => '127190;',
        "klöver 7" => '127191;',
        "klöver 8" => '127192;',
        "klöver 9" => '127193;',
        "klöver 10" => '127194;',
        "klöver knekt" => '127195;',
        "klöver dam" => '127197;',
        "klöver kung" => '127198;',
        "ruter ess" => '127169;',
        "ruter 2" => '127170;',
        "ruter 3" => '127171;',
        "ruter 4" => '127172;',
        "ruter 5" => '127173;',
        "ruter 6" => '127174',
        "ruter 7" => '127175;',
        "ruter 8" => '127176;',
        "ruter 9" => '127177;',
        "ruter 10" => '127178;',
        "ruter knekt" => '127179;',
        "ruter dam" => '127181;',
        "ruter kung" => '127182;',
        "hjärter ess" => '127153;',
        "hjärter 2" => '127154;',
        "hjärter 3" => '127155;',
        "hjärter 4" => '127156;',
        "hjärter 5" => '127157;',
        "hjärter 6" => '127158;',
        "hjärter 7" => '127159;',
        "hjärter 8" => '127160;',
        "hjärter 9" => '127161;',
        "hjärter 10" => '127162;',
        "hjärter knekt" => '127163;',
        "hjärter dam" => '127165;',
        "hjärter kung" => '127166;',
        "spader ess" => '127137;',
        "spader 2" => '127138;',
        "spader 3" => '127139;',
        "spader 4" => '127140;',
        "spader 5" => '127141;',
        "spader 6" => '127142;',
        "spader 7" => '127143;',
        "spader 8" => '127144;',
        "spader 9" => '127145;',
        "spader 10" => '127146;',
        "spader knekt" => '127147;',
        "spader dam" => '127149;',
        "spader kung" => '127150;'
    ];

    public function __construct(
        string $suite = null,
        int $number = null
    ) {
        parent::__construct($suite, $number);
    }

    /**
     * @return string
     */
    public function getAsString()
    {
        return $this->representation[join(" ", $this->value)];
    }
}
