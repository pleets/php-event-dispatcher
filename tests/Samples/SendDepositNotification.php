<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class SendDepositNotification extends Listener
{
    public function handle(Event $event): void
    {
        echo $event->text = 'Event changed!';
    }
}
