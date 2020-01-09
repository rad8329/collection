<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

/**
 * Interface Splitable.
 */
interface Splitable
{
    /**
     * Split a collection using a callback.
     *
     * @param callable ...$callbacks
     *
     * @return \loophp\collection\Contract\Collection<mixed>
     */
    public function split(callable ...$callbacks): Base;
}
