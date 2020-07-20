<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class LogDepositNotification extends Listener
{
    public function handle(Event $event): void
    {
        $event->text = 'Event changed again!';
    }
}
