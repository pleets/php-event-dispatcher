<?php

namespace Pleets\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

class Dispatcher implements EventDispatcherInterface
{
    private ListenerProvider $provider;

    public function __construct(ListenerProvider $provider)
    {
        $this->provider = $provider;
    }

    public function dispatch(object $event)
    {
        $listeners = $this->provider->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            (new $listener())->handle($event);
        }
    }
}
