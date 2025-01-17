<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Operation;

use Amp\Sync\LocalSemaphore;
use Closure;
use Exception;
use Generator;
use Iterator;

use function Amp\Iterator\fromIterable;
use function Amp\ParallelFunctions\parallel;
use function Amp\Promise\wait;
use function Amp\Sync\ConcurrentIterator\map;
use function function_exists;

// phpcs:disable
if (false === function_exists('Amp\ParallelFunctions\parallel')) {
    throw new Exception('You need amphp/parallel-functions to get this operation working.');
}
// phpcs:enable
/**
 * Class AsyncMap.
 *
 * @template TKey
 * @template T
 *
 * phpcs:disable Generic.Files.LineLength.TooLong
 */
final class AsyncMap extends AbstractOperation
{
    /**
     * @return Closure(callable(T, TKey): T ...): Closure(Iterator<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @param callable(T, TKey): T ...$callbacks
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
                    $callbackFactory =
                        /**
                         * @param TKey $key
                         *
                         * @return Closure(T, callable(T, TKey): T): T
                         */
                        static fn ($key): Closure =>
                            /**
                             * @param T $carry
                             * @param callable(T, TKey): T $callback
                             *
                             * @return T
                             */
                            static fn ($carry, callable $callback) => $callback($carry, $key);

                    $callback =
                        /**
                         * @param array{0: TKey, 1:T} $value
                         *
                         * @return array{0: TKey, 1: T}
                         */
                        static function (array $value) use ($callbacks, $callbackFactory): array {
                            [$key, $value] = $value;

                            return [$key, array_reduce($callbacks, $callbackFactory($key), $value)];
                        };

                    $iter = map(fromIterable(Pack::of()($iterator)), new LocalSemaphore(32), parallel($callback));

                    while (wait($iter->advance())) {
                        /** @var array{0: TKey, 1: T} $item */
                        $item = $iter->getCurrent();

                        yield $item[0] => $item[1];
                    }
                };
    }
}
