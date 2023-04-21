<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

use PHPUnit\Framework\TestCase;
use function DeepCopy\deep_copy;

/**
 * @internal
 */
abstract class LRUMemoizerTest extends TestCase
{
    /**
     * @param callable(): mixed $factory
     */
    private static function viewMemoizedValue(LRUMemoizer $memoizer, string $key, callable $factory): mixed
    {
        return deep_copy($memoizer)->get($key, $factory);
    }

    final public function testItSavesValue(): void
    {
        $memoizer = $this->createLRUMemoizer(capacity: 10);
        $memoizer->get('a', static fn (): string => 'a1');

        $a = self::viewMemoizedValue($memoizer, 'a', static fn (): string => 'a2');

        self::assertSame($a, 'a1');
    }

    final public function testItPrunes(): void
    {
        $memoizer = $this->createLRUMemoizer(capacity: 10);
        $memoizer->get('a', static fn (): string => 'a1');

        $memoizer->prune();
        $a = self::viewMemoizedValue($memoizer, 'a', static fn (): string => 'a2');

        self::assertSame($a, 'a2');
    }

    final public function testItTakesCapacityIntoAccount(): void
    {
        $memoizer = $this->createLRUMemoizer(capacity: 2);
        $memoizer->get('a', static fn (): string => 'a1');
        $memoizer->get('b', static fn (): string => 'b1');
        $memoizer->get('c', static fn (): string => 'c1');

        $a = self::viewMemoizedValue($memoizer, 'a', static fn (): string => 'a2');
        $b = self::viewMemoizedValue($memoizer, 'b', static fn (): string => 'b2');
        $c = self::viewMemoizedValue($memoizer, 'c', static fn (): string => 'c2');

        self::assertSame($a, 'a2');
        self::assertSame($b, 'b1');
        self::assertSame($c, 'c1');
    }

    final public function testItRemovesLeastRecentlyUsed(): void
    {
        $memoizer = $this->createLRUMemoizer(capacity: 2);
        $memoizer->get('a', static fn (): string => 'a1');
        $memoizer->get('b', static fn (): string => 'b1');
        $memoizer->get('a', static fn (): string => 'a1');
        $memoizer->get('c', static fn (): string => 'c1');

        $a = self::viewMemoizedValue($memoizer, 'a', static fn (): string => 'a2');
        $b = self::viewMemoizedValue($memoizer, 'b', static fn (): string => 'b2');
        $c = self::viewMemoizedValue($memoizer, 'c', static fn (): string => 'c2');

        self::assertSame($a, 'a1');
        self::assertSame($b, 'b2');
        self::assertSame($c, 'c1');
    }

    /**
     * @param positive-int $capacity
     */
    abstract protected function createLRUMemoizer(int $capacity): LRUMemoizer;
}
