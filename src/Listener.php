<?php

namespace Pleets\EventDispatcher;

abstract class Listener
{
    abstract public function handle(Event $event): void;
}
