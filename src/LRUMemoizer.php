<?php

declare(strict_types=1);

namespace Phpyh\LRUMemoizer;

final class LRUMemoizer
{
    public const MAX_ITEMS_DEFAULT = 100;

    /**
     * @var array<string, mixed>
     */
    private array $itemsByKey = [];

    /**
     * @param positive-int $maxItems
     */
    public function __construct(
        private readonly int $maxItems = self::MAX_ITEMS_DEFAULT,
    ) {
    }

    /**
     * @template T
     * @param callable(): T $factory
     * @return T
     */
    public function get(string $key, callable $factory): mixed
    {
        if (\array_key_exists($key, $this->itemsByKey)) {
            /** @var T */
            $value = $this->itemsByKey[$key];
            unset($this->itemsByKey[$key]);
            $this->itemsByKey[$key] = $value;

            return $value;
        }

        $value = $factory();
        $this->itemsByKey[$key] = $value;

        if (\count($this->itemsByKey) > $this->maxItems) {
            array_shift($this->itemsByKey);
        }

        return $value;
    }

    public function prune(): void
    {
        $this->itemsByKey = [];
    }
}
