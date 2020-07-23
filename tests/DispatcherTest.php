<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Pleets\EventDispatcher\Dispatcher;
use Pleets\EventDispatcher\ListenerProvider;
use Tests\Samples\DepositEvent;
use Tests\Samples\LogDepositNotification;
use Tests\Samples\SendDepositNotification;
use Tests\Samples\SetTextListener;
use Tests\Samples\StoppableEvent;
use Tests\Samples\TrimTextListener;
use Tests\Samples\UnsetTextListener;

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

    /** @test */
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

    /** @test */
    public function itReturnsTheEventAndNotExecuteAnyListenerIfTheEventIsStoppable()
    {
        $provider = new ListenerProvider();
        $stoppable = new StoppableEvent();
        $stoppable->stopPropagation();

        $provider->subscribe($stoppable, new UnsetTextListener());

        $dispatcher = new Dispatcher($provider);
        $event = $dispatcher->dispatch($stoppable);

        $this->assertInstanceOf(StoppableEvent::class, $event);
        $this->assertSame('Hello', $stoppable->text);
    }

    /** @test */
    public function itReturnsTheEventAndNotExecuteAnyListenerAfterStopPropagation()
    {
        $provider = new ListenerProvider();
        $stoppable = new StoppableEvent();

        $provider->subscribe($stoppable, new UnsetTextListener());
        $provider->subscribe($stoppable, new SetTextListener());

        $dispatcher = new Dispatcher($provider);
        $event = $dispatcher->dispatch($stoppable);

        $this->assertInstanceOf(StoppableEvent::class, $event);
        $this->assertSame('', $stoppable->text);
    }

    /** @test */
    public function itCanRunAllListenersInAStoppableEventsWhenItIsNotStopped()
    {
        $provider = new ListenerProvider();
        $stoppable = new StoppableEvent();

        $provider->subscribe($stoppable, new SetTextListener());
        $provider->subscribe($stoppable, new TrimTextListener());

        $dispatcher = new Dispatcher($provider);
        $event = $dispatcher->dispatch($stoppable);

        $this->assertInstanceOf(StoppableEvent::class, $event);
        $this->assertSame('Hello World', $stoppable->text);
    }
}
