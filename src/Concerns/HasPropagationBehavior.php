<?php

namespace Pleets\EventDispatcher\Concerns;

trait HasPropagationBehavior
{
    private bool $propagation = true;

    public function isPropagationStopped(): bool
    {
        return !$this->propagation;
    }

    public function stopPropagation(): void
    {
        $this->propagation = false;
    }
}
