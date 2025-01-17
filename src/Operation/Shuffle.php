<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Iterator;
use loophp\collection\Iterator\RandomIterator;

/**
 * @template TKey
 * @template T
 */
final class Shuffle extends AbstractOperation
{
    /**
     * @return Closure(int): Closure(Iterator<TKey, T>): Iterator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @return Closure(Iterator<TKey, T>): Iterator<TKey, T>
             */
            static fn (int $seed): Closure =>
                /**
                 * @param Iterator<TKey, T> $iterator
                 *
                 * @return Iterator<TKey, T>
                 */
                static fn (Iterator $iterator): Iterator => new RandomIterator($iterator, $seed);
    }
}
