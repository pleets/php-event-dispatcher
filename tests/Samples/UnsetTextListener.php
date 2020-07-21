<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class UnsetTextListener extends Listener
{
    public function handle(Event $event): void
    {
        $event->text = '';
        $event->stopPropagation();
    }
}
