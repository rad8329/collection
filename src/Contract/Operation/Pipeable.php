<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use Generator;
use Iterator;
use loophp\collection\Contract\Collection;

/**
 * @template TKey
 * @template T
 */
interface Pipeable
{
    /**
     * @param callable(Iterator<TKey, T>): Generator<TKey, T> ...$callbacks
     *
     * @return Collection<TKey, T>
     */
    public function pipe(callable ...$callbacks): Collection;
}
