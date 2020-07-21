<?php

namespace Pleets\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class Dispatcher implements EventDispatcherInterface
{
    private ListenerProvider $provider;

    public function __construct(ListenerProvider $provider)
    {
        $this->provider = $provider;
    }

    public function dispatch(object $event)
    {
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return $event;
        }

        $listeners = $this->provider->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            (new $listener())->handle($event);

            if ($event instanceof StoppableEventInterface) {
                break;
            }
        }

        return $event;
    }
}
