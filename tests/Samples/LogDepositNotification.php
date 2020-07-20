<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class LogDepositNotification extends Listener
{
    public function handle(Event $event): void
    {
        // code to log this event
    }
}
