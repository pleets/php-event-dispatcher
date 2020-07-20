<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;

class PaymentEvent extends Event
{
    public string $text;

    protected string $amount;

    private string $currency = 'USD';

    public function __construct($amount)
    {
        $this->amount = $amount;
        $this->text = 'Your payment: '.$this->amount.$this->currency;
    }
}
