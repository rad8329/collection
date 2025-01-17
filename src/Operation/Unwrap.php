<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use Iterator;

/**
 * @template TKey
 * @template T
 */
final class Unwrap extends AbstractOperation
{
    /**
     * @return Closure(Iterator<TKey, array<TKey, T>>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        /** @var Closure(Iterator<TKey, array<TKey, T>>): Generator<TKey, T> $flatten */
        $flatten = Flatten::of()(1);

        // Point free style.
        return $flatten;
    }
}
