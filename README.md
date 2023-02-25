# PHPyh LRU Memoizer

## Installation

```shell
composer require phpyh/lru-memoizer
```

## Usage

```php
use PHPyh\LRUMemoizer\LRUMemoizer;

final class Metadata {}

final class MetadataFactory
{
    public function __construct(
        private readonly LRUMemoizer $memoizer = new LRUMemoizer(maxItems: 25),
    ) {
    }

    /**
     * @param ?class-string $class
     */
    public function metadataFor(string $class): Metadata
    {
        return $this->memoizer->get($class, static fn (): Metadata => new Metadata());
    }
}
```
