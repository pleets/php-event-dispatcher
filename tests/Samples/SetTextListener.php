<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class SetTextListener extends Listener
{
    public function handle(Event $event): void
    {
        $event->text = 'Hello World';
    }
}
