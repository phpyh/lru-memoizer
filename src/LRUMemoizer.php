<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

interface LRUMemoizer
{
    /**
     * @template T
     * @param callable(): T $factory
     * @return T
     */
    public function get(string $key, callable $factory): mixed;

    public function prune(): void;
}
