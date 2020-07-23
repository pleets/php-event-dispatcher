<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Tests\Samples\DepositEvent;
use Tests\Samples\SendDepositNotification;

class ListenerTest extends TestCase
{
    /** @test */
    public function aListenerCanReceiveAnEventAndChangeIt()
    {
        $listener = new SendDepositNotification();
        $event = new DepositEvent('127.00');

        $listener->handle($event);

        $this->assertEquals('Event changed!', $event->text);
    }
}
