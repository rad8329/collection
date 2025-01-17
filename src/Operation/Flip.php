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
final class Flip extends AbstractOperation
{
    /**
     * @return Closure(Iterator<TKey, T>): Generator<T, TKey>
     */
    public function __invoke(): Closure
    {
        $callbackForKeys =
            /**
             * @param mixed $carry
             * @param TKey $key
             * @param T $value
             *
             * @return T
             */
            static fn ($carry, $key, $value) => $value;

        $callbackForValues =
            /**
             * @param mixed $carry
             * @param TKey $key
             *
             * @return TKey
             */
            static fn ($carry, $key) => $key;

        /** @var Closure(Iterator<TKey, T>): Generator<T, TKey> $associate */
        $associate = Associate::of()($callbackForKeys)($callbackForValues);

        // Point free style.
        return $associate;
    }
}
