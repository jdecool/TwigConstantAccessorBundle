<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantCollection implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @param array<array-key, ConstantAccessor> $constants
     */
    public function __construct(
        private array $constants = [],
    ) {
    }

    /**
     * Add constant from array configuration.
     */
    public function addFromArray(array $configuration): ConstantCollection
    {
        return $this->add(new ConstantAccessor($configuration));
    }

    /**
     * Add constant accessor to collection.
     */
    public function add(ConstantAccessor $accessor): ConstantCollection
    {
        $this->constants[$accessor->getKey()] = $accessor;

        return $this;
    }

    /**
     * Transform the collection in array.
     */
    public function toArray(): array
    {
        return array_map(
            static fn (ConstantAccessor $accessor): array => $accessor->toArray(),
            $this->constants,
        );
    }

    public function offsetExists($offset): bool
    {
        return isset($this->constants[$offset]);
    }

    public function offsetGet($offset): ConstantAccessor
    {
        return $this->constants[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof ConstantAccessor) {
            throw new \InvalidArgumentException(sprintf('Expected instance of "%s", "%s" given.', ConstantAccessor::class, get_debug_type($value)));
        }

        $this->constants[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->constants[$offset]);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->constants);
    }
}
