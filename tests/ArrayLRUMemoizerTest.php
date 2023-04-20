<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

/**
 * @internal
 * @covers \PHPyh\LRUMemoizer\ArrayLRUMemoizer
 */
final class ArrayLRUMemoizerTest extends LRUMemoizerTest
{
    protected function createLRUMemoizer(int $capacity): LRUMemoizer
    {
        return new ArrayLRUMemoizer(capacity: $capacity);
    }
}
