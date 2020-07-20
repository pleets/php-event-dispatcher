<?php

namespace Pleets\EventDispatcher;

use Pleets\EventDispatcher\Concerns\HasNotAccessibleValues;
use ReflectionClass;
use ReflectionProperty;

abstract class Event
{
    use HasNotAccessibleValues;

    /**
     * Prepare the instance values for serialization.
     *
     * @return array
     */
    public function __serialize()
    {
        $values = [];

        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $this->toPropertyName($property);
            $values[$name] = $this->getPropertyValue($property);
        }

        return $values;
    }

    /**
     * Restore the model after serialization.
     *
     * @return array
     */
    public function __unserialize(array $values)
    {
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $this->toPropertyName($property);

            if (!array_key_exists($name, $values)) {
                continue;
            }

            $property->setAccessible(true);
            $property->setValue($this, $values[$name]);
        }

        return $values;
    }

    protected function toPropertyName(ReflectionProperty $property): string
    {
        $name = $property->getName();

        if ($property->isPrivate()) {
            $name = "\0-\0{$name}";
        } elseif ($property->isProtected()) {
            $name = "\0*\0{$name}";
        }

        return $name;
    }
}
