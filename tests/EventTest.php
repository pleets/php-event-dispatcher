<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Pleets\EventDispatcher\Concerns\HasNotAccessibleValues;
use ReflectionClass;
use Tests\Samples\DepositEvent;

/**
 * @internal
 * @coversNothing
 */
class EventTest extends TestCase
{
    use HasNotAccessibleValues;

    /** @test */
    public function serializing()
    {
        $event = new DepositEvent('127.00');
        $serialized = serialize($event);

        $obj = unserialize($serialized);
        $properties = (new ReflectionClass($obj))->getProperties();

        $currencyProperty = array_filter($properties, function ($property) {
            return 'currency' === $property->getName();
        });
        $currencyProperty = array_shift($currencyProperty);

        $amountProperty = array_filter($properties, function ($property) {
            return 'amount' === $property->getName();
        });
        $amountProperty = array_shift($amountProperty);

        $textProperty = array_filter($properties, function ($property) {
            return 'text' === $property->getName();
        });
        $textProperty = array_shift($textProperty);

        $this->assertTrue($currencyProperty->isPrivate());
        $this->assertEquals('USD', $this->getPropertyValue($currencyProperty, $obj));

        $this->assertTrue($amountProperty->isProtected());
        $this->assertEquals('127.00', $this->getPropertyValue($amountProperty, $obj));

        $this->assertTrue($textProperty->isPublic());
        $this->assertEquals('Your deposite: 127.00USD', $textProperty->getValue($obj));
    }

    /** @test */
    public function itSupportsLosslessSerialization()
    {
        $event = new DepositEvent('127.00');

        $this->assertTrue($event == unserialize(serialize($event)));
    }
}
