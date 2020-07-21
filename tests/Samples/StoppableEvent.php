<?php

namespace Tests\Samples;

use Pleets\EventDispatcher\Concerns\HasPropagationBehavior;
use Pleets\EventDispatcher\Event;
use Psr\EventDispatcher\StoppableEventInterface;

class StoppableEvent extends Event implements StoppableEventInterface
{
    use HasPropagationBehavior;

    public string $text = 'Hello';
}
