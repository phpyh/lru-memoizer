# PHPyh LRU Memoizer

## Installation

```shell
composer require phpyh/lru-memoizer
```

## Usage

```php
use PHPyh\LRUMemoizer\ArrayLRUMemoizer;

final class Metadata {}

final class MetadataFactory
{
    public function __construct(
        private readonly ArrayLRUMemoizer $memoizer = new ArrayLRUMemoizer(capacity: 25),
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
