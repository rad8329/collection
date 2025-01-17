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
 *
 * phpcs:disable Generic.Files.LineLength.TooLong
 */
final class Apply extends AbstractOperation
{
    /**
     * @return Closure(callable(T, TKey, Iterator<TKey, T>):bool ...): Closure(Iterator<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param callable(T, TKey, Iterator<TKey, T>): bool ...$callbacks
             *
             * @return Closure(Iterator<TKey, T>): Generator<TKey, T>
             */
            static fn (callable ...$callbacks): Closure =>
                /**
                 * @param Iterator<TKey, T> $iterator
                 *
                 * @return Generator<TKey, T>
                 */
                static function (Iterator $iterator) use ($callbacks): Generator {
                    foreach ($iterator as $key => $value) {
                        foreach ($callbacks as $cKey => $callback) {
                            $result = $callback($value, $key, $iterator);

                            if (false === $result) {
                                unset($callbacks[$cKey]);
                            }
                        }

                        yield $key => $value;
                    }
                };
    }
}
