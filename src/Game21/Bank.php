<?php

namespace App\Game21;

use App\Card\Card;
use App\Card\CardCollection;

class Bank extends Player
{
    public function __construct()
    {
        parent::__construct("bank");
    }
}
