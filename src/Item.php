<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

/**
 * @internal
 * @psalm-internal PHPyh\LRUMemoizer
 * @template T
 */
final class Item
{
    /**
     * @param T $value
     */
    public function __construct(
        public string $key,
        public mixed $value,
        public ?self $older = null,
        public ?self $newer = null,
    ) {
    }
}
