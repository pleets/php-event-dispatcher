<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Pleets\EventDispatcher\Exceptions\EventNotRegisteredException;
use Pleets\EventDispatcher\Exceptions\ListenerNotFoundException;
use Pleets\EventDispatcher\Exceptions\ListenerNotRegisteredException;
use Pleets\EventDispatcher\ListenerProvider;
use Tests\Samples\DepositEvent;
use Tests\Samples\LogDepositNotification;
use Tests\Samples\SendDepositNotification;
use Tests\Samples\SendPaymentNotification;

/**
 * @internal
 * @coversNothing
 */
class ListenerProviderTest extends TestCase
{
    /** @test */
    public function itStoresTheFullyQualifiedNameAsTheListenerReference()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, new SendDepositNotification());

        $this->assertSame([SendDepositNotification::class], $provider->getListenersForEvent($deposit));
    }

    /** @test */
    public function itSubscribesListenersInTheOrderItWereRegistered()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, new SendDepositNotification());
        $provider->subscribe($deposit, new LogDepositNotification());

        $this->assertSame([
            SendDepositNotification::class,
            LogDepositNotification::class,
        ], $provider->getListenersForEvent($deposit));
    }

    /** @test */
    public function itCanSubscribeListenersByListenerClassName()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, SendDepositNotification::class);

        $this->assertSame([SendDepositNotification::class], $provider->getListenersForEvent($deposit));
    }

    /** @test */
    public function itThrowsAnExceptionWhenListenerClassNameDoesNotExistsInSubscriptionAction()
    {
        $this->expectException(ListenerNotFoundException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, 'some_not_existing_class');
    }

    /** @test */
    public function itThrowsAnExceptionWhenListenerClassNameDoesNotExistsInUnsubscriptionAction()
    {
        $this->expectException(ListenerNotFoundException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');
        $provider->subscribe($deposit, SendDepositNotification::class);

        $provider->unsubscribe($deposit, 'some_not_existing_class');
    }

    /** @test */
    public function itCanUnsubscribeListenersByListenerInstance()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $sendDepositNotification = new SendDepositNotification();

        $provider->subscribe($deposit, $sendDepositNotification);
        $provider->subscribe($deposit, new LogDepositNotification());
        $provider->unsubscribe($deposit, $sendDepositNotification);

        // note that key was not modified
        $this->assertSame([1 => LogDepositNotification::class], $provider->getListenersForEvent($deposit));
    }

    /** @test */
    public function itCanUnsubscribeListenersByListenerClassName()
    {
        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->subscribe($deposit, new SendDepositNotification());
        $provider->subscribe($deposit, new LogDepositNotification());
        $provider->unsubscribe($deposit, SendDepositNotification::class);

        // note that key was not modified
        $this->assertSame([1 => LogDepositNotification::class], $provider->getListenersForEvent($deposit));
    }

    /** @test */
    public function itThrowsAnExceptionWhenTryToUnsubscribeAListenerInANonRegisteredEventByInstance()
    {
        $this->expectException(EventNotRegisteredException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->unsubscribe($deposit, new SendDepositNotification());
    }

    /** @test */
    public function itThrowsAnExceptionWhenTryToUnsubscribeAListenerInANonRegisteredEventByClassName()
    {
        $this->expectException(EventNotRegisteredException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');

        $provider->unsubscribe($deposit, SendDepositNotification::class);
    }

    /** @test */
    public function itThrowsAnExceptionWhenTryToUnsubscribeAListenerThatIsNotRegisteredByInstance()
    {
        $this->expectException(ListenerNotRegisteredException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');
        $provider->subscribe($deposit, new SendDepositNotification());

        $provider->unsubscribe($deposit, new SendPaymentNotification());
    }

    /** @test */
    public function itThrowsAnExceptionWhenTryToUnsubscribeAListenerThatIsNotRegisteredByClassName()
    {
        $this->expectException(ListenerNotRegisteredException::class);

        $provider = new ListenerProvider();
        $deposit = new DepositEvent('127.00');
        $provider->subscribe($deposit, SendDepositNotification::class);

        $provider->unsubscribe($deposit, SendPaymentNotification::class);
    }
}
