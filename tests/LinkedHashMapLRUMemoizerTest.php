<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

/**
 * @internal
 * @covers \PHPyh\LRUMemoizer\LinkedHashMapLRUMemoizer
 */
final class LinkedHashMapLRUMemoizerTest extends LRUMemoizerTest
{
    protected function createLRUMemoizer(int $capacity): LRUMemoizer
    {
        return new LinkedHashMapLRUMemoizer(capacity: $capacity);
    }
}
