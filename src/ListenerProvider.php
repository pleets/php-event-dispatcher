<?php

namespace Pleets\EventDispatcher;

use Pleets\EventDispatcher\Exceptions\EventNotRegisteredException;
use Pleets\EventDispatcher\Exceptions\ListenerNotFoundException;
use Pleets\EventDispatcher\Exceptions\ListenerNotRegisteredException;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @method subscribe(Event $deposit, mixed $class)
 * @method unsubscribe(Event $deposit, mixed $class)
 */
class ListenerProvider implements ListenerProviderInterface
{
    public array $eventListeners = [];

    public function __call($name, $args)
    {
        switch ($name) {
            case 'subscribe':
                switch (gettype($args[1])) {
                    case 'object':
                        return call_user_func_array([$this, 'subscribeByListenerInstance'], $args);
                    case 'string':
                        return call_user_func_array([$this, 'subscribeByListenerClassName'], $args);
                }

                break;
            case 'unsubscribe':
                switch (gettype($args[1])) {
                    case 'object':
                        return call_user_func_array([$this, 'unsubscribeByListenerInstance'], $args);
                    case 'string':
                        return call_user_func_array([$this, 'unsubscribeByListenerClassName'], $args);
                }

                break;
        }

        throw new \ErrorException('Call to undefined method '.__CLASS__.'::'.$name);
    }

    public function getListenersForEvent(object $event): iterable
    {
        return $this->eventListeners[$this->getEventKey($event)] ?? [];
    }

    public function subscribeByListenerInstance(Event $event, Listener $listener): void
    {
        $key = $this->getEventKey($event);

        if (!array_key_exists($key, $this->eventListeners)) {
            $this->eventListeners[$key] = [];
        }

        $this->eventListeners[$key][] = $this->getListenerKey($listener);
    }

    public function subscribeByListenerClassName(Event $event, string $listener): void
    {
        $key = $this->getEventKey($event);

        if (!array_key_exists($key, $this->eventListeners)) {
            $this->eventListeners[$key] = [];
        }

        if (!class_exists($listener)) {
            throw new ListenerNotFoundException('The specified listener does not exists: '.$listener);
        }

        $this->eventListeners[$key][] = $listener;
    }

    public function unsubscribeByListenerInstance(Event $event, Listener $listener): void
    {
        $key = $this->getEventKey($event);

        if (!array_key_exists($key, $this->eventListeners)) {
            throw new EventNotRegisteredException(
                'The listener '.get_class($event).' is not subscribed to '.get_class($event)
            );
        }

        $listeners = $this->eventListeners[$key];
        $eventListenerKey = array_search($this->getListenerKey($listener), $listeners);

        if (false === $eventListenerKey) {
            throw new ListenerNotRegisteredException(
                'The listener '.get_class($event).' is not subscribed to '.get_class($event)
            );
        }

        unset($this->eventListeners[$key][$eventListenerKey]);
    }

    public function unsubscribeByListenerClassName(Event $event, string $listener): void
    {
        $key = $this->getEventKey($event);

        if (!array_key_exists($key, $this->eventListeners)) {
            throw new EventNotRegisteredException(
                'The event '.get_class($event).' is not registered'
            );
        }

        $listeners = $this->eventListeners[$key];

        if (!class_exists($listener)) {
            throw new ListenerNotFoundException('The specified listener does not exists: '.$listener);
        }

        $eventListenerKey = array_search($listener, $listeners);  // TODO: what happens if the key does not exists

        if (false === $eventListenerKey) {
            throw new ListenerNotRegisteredException(
                'The listener '.get_class($event).' is not subscribed to '.get_class($event)
            );
        }

        unset($this->eventListeners[$key][$eventListenerKey]);
    }

    private function getEventKey(Event $event): string
    {
        return get_class($event);
    }

    private function getListenerKey(Listener $listener): string
    {
        return get_class($listener);
    }
}
