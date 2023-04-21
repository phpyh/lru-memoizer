<?php

declare(strict_types=1);

namespace PHPyh\LRUMemoizer;

final class LinkedHashMapLRUMemoizer implements LRUMemoizer
{
    public const CAPACITY_DEFAULT = 100;

    /**
     * @var array<string, Item>
     */
    private array $itemsByKey = [];
    private ?Item $oldest = null;
    private ?Item $newest = null;

    /**
     * @param positive-int $capacity
     */
    public function __construct(
        private int $capacity = self::CAPACITY_DEFAULT,
    ) {
    }

    /**
     * @template T
     * @param callable(): T $factory
     * @return T
     */
    public function get(string $key, callable $factory): mixed
    {
        if (isset($this->itemsByKey[$key])) {
            /** @var Item<T> */
            $item = $this->itemsByKey[$key];
            $this->makeItemNewest($item);

            return $item->value;
        }

        $item = new Item($key, $factory());
        $this->addNewestItem($item);
        $this->ensureCapacity();

        return $item->value;
    }

    public function prune(): void
    {
        $this->itemsByKey = [];
        $this->oldest = null;
        $this->newest = null;
    }

    private function makeItemNewest(Item $item): void
    {
        if ($item->newer === null) {
            return;
        }

        \assert($this->newest !== null);

        if ($item->older === null) {
            $this->oldest = $item->newer;
        } else {
            $item->older->newer = $item->newer;
        }

        $item->newer->older = $item->older;
        $this->newest->newer = $item;
        $item->older = $this->newest;
        $item->newer = null;
        $this->newest = $item;
    }

    private function addNewestItem(Item $item): void
    {
        $this->itemsByKey[$item->key] = $item;

        if ($this->newest === null) {
            $this->newest = $item;
            $this->oldest = $item;

            return;
        }

        $this->newest->newer = $item;
        $item->older = $this->newest;
        $this->newest = $item;
    }

    private function ensureCapacity(): void
    {
        if (\count($this->itemsByKey) <= $this->capacity) {
            return;
        }

        \assert($this->oldest !== null);
        \assert($this->oldest->newer !== null);

        unset($this->itemsByKey[$this->oldest->key]);
        $newOldestItem = $this->oldest->newer;
        $newOldestItem->older = null;
        $this->oldest = $newOldestItem;
    }
}
