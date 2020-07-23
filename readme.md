# PRR-14 Event Dispatcher

Event Dispatching is a common and well-tested mechanism to allow developers to inject logic into an application easily and consistently.
This library was developed according to PSR-14 and all interfaces in psr/event-dispatcher were implemented.

You can download this project as follows.

```bash
git clone git@github.com:pleets/php-event-dispatcher.git
```

# Usage

## Creating Events

An Event is a message produced by an Emitter. It may be any arbitrary PHP object.
You can create an event extending the `Event` class.

```php
use Pleets\EventDispatcher\Event;

class DepositEvent extends Event
{
    public string $text;

    protected string $amount;

    private string $currency = 'USD';

    public function __construct($amount)
    {
        $this->amount = $amount;
        $this->text = 'We are preparing your deposit: '.$this->amount.$this->currency;
    }
}
```

## Creating Listeners

A Listener is any PHP callable that expects to be passed an Event. Zero or more Listeners may be passed the same Event.
A Listener MAY enqueue some other asynchronous behavior if it so chooses.
You can create a listener extending the `Listener` class.

```php
use Pleets\EventDispatcher\Event;
use Pleets\EventDispatcher\Listener;

class SendDepositNotification extends Listener
{
    public function handle(Event $event): void
    {
        $event->text = 'Your deposit was done!';
    }
}
```

## Creating Listener Providers and subscribing events

A Listener Provider is responsible for determining what Listeners are relevant for a given Event, but MUST NOT call the Listeners itself.
A Listener Provider may specify zero or more relevant Listeners.
You can create a listener provider as follows.

```php
$provider = new ListenerProvider();

$deposit = new DepositEvent('127.00');
$provider->subscribe($deposit, new SendDepositNotification());
```

## Dispatching your events

A Dispatcher is a service object that is given an Event object by an Emitter.
The Dispatcher is responsible for ensuring that the Event is passed to all relevant Listeners, but MUST defer determining the responsible listeners to a Listener Provider.
You can dispatch you events as follows.

```php
$dispatcher = new Dispatcher($provider);
$dispatcher->dispatch($deposit);
```

All listener will be informed about this event and will be executed.