<?php

namespace Pleets\EventDispatcher\Concerns;

use ReflectionProperty;

trait HasNotAccessibleValues
{
    /**
     * Get the property value for the given property.
     *
     * @param null $object
     *
     * @return mixed
     */
    protected function getPropertyValue(ReflectionProperty $property, $object = null)
    {
        $object = $object ?? $this;

        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
