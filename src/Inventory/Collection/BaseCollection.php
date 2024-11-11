<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Collection;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @psalm-template TKey of array-key
 * @psalm-template TValue
 * @template-covariant TValue
 * @psalm-consistent-constructor
 * @template-implements IteratorAggregate<TKey, TValue>
 */
class BaseCollection implements Countable, IteratorAggregate
{
    /** @psalm-var array<TKey, TValue> */
    private array $elements;

    /**
     * Constructor.
     *
     * @psalm-param array<TKey, TValue> $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @param TValue $element
     */
    public function add($element): static
    {
        $this->elements[] = $element;

        return $this;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Gets an iterator for the elements.
     *
     * @return ArrayIterator<TKey, TValue> The iterator.
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function filter(callable $callback): static
    {
        return new static(
            array_values(
                array_filter($this->elements, $callback)
            )
        );
    }

    /**
     * @psalm-template U
     * @psalm-param callable(TValue): U $callback
     *
     * @return BaseCollection<TKey, U>
     */
    public function map(callable $callback): BaseCollection
    {
        return new BaseCollection(
            array_map($callback, $this->elements)
        );
    }

    /**
     * Sorts the collection by a callback, does not create a new collection
     */
    public function sort(callable $callback): static
    {
        usort($this->elements, $callback);
        return $this;
    }

    /**
     * @return TValue[]
     */
    public function toArray(): array
    {
        return $this->elements;
    }
}