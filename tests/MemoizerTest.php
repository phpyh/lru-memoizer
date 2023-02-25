<?php

declare(strict_types=1);

namespace Phpyh\LRUMemoizer;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @covers \Phpyh\LRUMemoizer\LRUMemoizer
 */
final class MemoizerTest extends TestCase
{
    /**
     * @param callable(): mixed $factory
     */
    private static function assertMemoizedValueSame(LRUMemoizer $memoizer, string $key, callable $factory, mixed $expectedValue): void
    {
        $memoizer = clone $memoizer;
        $actualValue = $memoizer->get($key, $factory);

        assertSame($actualValue, $expectedValue);
    }

    public function testItSavesValue(): void
    {
        $memoizer = new LRUMemoizer();

        $memoizer->get('a', static fn (): string => 'a1');

        self::assertMemoizedValueSame($memoizer, 'a', static fn (): string => 'a2', 'a1');
    }

    public function testItPrunes(): void
    {
        $memoizer = new LRUMemoizer();
        $memoizer->get('a', static fn (): string => 'a1');

        $memoizer->prune();

        self::assertMemoizedValueSame($memoizer, 'a', static fn (): string => 'a2', 'a2');
    }

    public function testItDiscardsLeastRecentlyUsedItems(): void
    {
        $memoizer = new LRUMemoizer(maxItems: 2);
        $memoizer->get('a', static fn (): string => 'a1');
        $memoizer->get('b', static fn (): string => 'b1');

        $memoizer->get('c', static fn (): string => 'c1');

        self::assertMemoizedValueSame($memoizer, 'a', static fn (): string => 'a2', 'a2');
        self::assertMemoizedValueSame($memoizer, 'b', static fn (): string => 'b2', 'b1');
        self::assertMemoizedValueSame($memoizer, 'c', static fn (): string => 'c2', 'c1');
    }
}
