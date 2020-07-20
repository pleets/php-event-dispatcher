<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;

class DepositEvent extends Event
{
    public string $text;

    protected string $amount;

    private string $currency = 'USD';

    public function __construct($amount)
    {
        $this->amount = $amount;
        $this->text = 'Your deposite: '.$this->amount.$this->currency;
    }
}
