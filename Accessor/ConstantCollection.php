<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantCollection implements \ArrayAccess, \IteratorAggregate
{
    /** @var ConstantAccessor[] */
    private array $contants;

    /**
     * Constructor.
     *
     * @param ConstantAccessor[] $constants
     */
    public function __construct(array $constants = [])
    {
        $this->contants = $constants;
    }

    /**
     * Add constant from array configuration.
     */
    public function addFromArray(array $configuration): ConstantCollection
    {
        return $this->add(new ConstantAccessor($configuration));
    }

    /**
     * Add constant accesor to collection.
     */
    public function add(ConstantAccessor $accessor): ConstantCollection
    {
        $this->contants[$accessor->getKey()] = $accessor;

        return $this;
    }

    /**
     * Transform the collection in array.
     */
    public function toArray(): array
    {
        return array_map(
            static fn (ConstantAccessor $accessor): array => $accessor->toArray(),
            $this->contants,
        );
    }

    public function offsetExists($offset): bool
    {
        return isset($this->contants[$offset]);
    }

    public function offsetGet($offset): ConstantAccessor
    {
        return $this->contants[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->contants[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->contants[$offset]);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->contants);
    }
}
