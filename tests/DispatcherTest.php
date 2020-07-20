<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Pleets\EventDispatcher\Dispatcher;
use Pleets\EventDispatcher\ListenerProvider;
use Tests\Samples\DepositEvent;
use Tests\Samples\LogDepositNotification;
use Tests\Samples\SendDepositNotification;

/**
 * @internal
 * @coversNothing
 */
class DispatcherTest extends TestCase
{
    /** @test */
    public function itCanExecuteARegisteredListenerForAnEvent()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, new SendDepositNotification());

        $dispatcher = new Dispatcher($provider);
        $dispatcher->dispatch($deposit);

        $this->assertSame('Event changed!', $deposit->text);
    }

    public function itCanExecutedListenersInOrderItWereRegistered()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, new SendDepositNotification());
        $provider->subscribe($deposit, new LogDepositNotification());

        $dispatcher = new Dispatcher($provider);
        $dispatcher->dispatch($deposit);

        $this->assertSame('Event changed again!', $deposit->text);
    }
}
