<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class TrimTextListener extends Listener
{
    public function handle(Event $event): void
    {
        $event->text = trim($event->text);
    }
}
