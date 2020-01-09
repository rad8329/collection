<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

/**
 * Interface Walkable.
 */
interface Walkable
{
    /**
     * Apply one or more supplied callbacks to every item of a collection.
     *
     * @param callable ...$callbacks
     *
     * @return \loophp\collection\Contract\Collection<mixed>
     */
    public function walk(callable ...$callbacks): Base;
}
