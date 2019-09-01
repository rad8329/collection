<?php

declare(strict_types=1);

namespace drupol\collection\Operation;

/**
 * Class Contains.
 */
final class Contains extends Operation
{
    /**
     * Contains constructor.
     *
     * @param mixed $key
     */
    public function __construct($key)
    {
        parent::__construct(...[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function on(iterable $collection)
    {
        [$key] = $this->parameters;

        if ((false === \is_string($key)) && (true === \is_callable($key))) {
            $placeholder = new \stdClass();

            return (new First($key, $placeholder))->on($collection) !== $placeholder;
        }

        foreach ($collection as $value) {
            if ($value === $key) {
                return true;
            }
        }

        return false;
    }
}