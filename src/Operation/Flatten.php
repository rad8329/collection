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
use loophp\collection\Iterator\IterableIterator;

/**
 * @template TKey
 * @template T
 */
final class Flatten extends AbstractOperation
{
    /**
     * @return Closure(int): Closure(Iterator<TKey, T>): Generator<int|TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @return Closure(Iterator<TKey, T>): Generator<int|TKey, T>
             */
            static fn (int $depth): Closure =>
                /**
                 * @param Iterator<TKey, T> $iterator
                 */
                static function (Iterator $iterator) use ($depth): Generator {
                    foreach ($iterator as $key => $value) {
                        if (false === is_iterable($value)) {
                            yield $key => $value;

                            continue;
                        }

                        if (1 !== $depth) {
                            /** @var callable(Iterator<TKey, T>): Generator<TKey, T> $flatten */
                            $flatten = Flatten::of()($depth - 1);

                            $value = $flatten(new IterableIterator($value));
                        }

                        yield from $value;
                    }
                };
    }
}
