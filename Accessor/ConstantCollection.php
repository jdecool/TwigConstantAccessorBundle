<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantCollection implements \ArrayAccess, \IteratorAggregate
{
    /** @var ConstantAccessor[] */
    private $contants;

    /**
     * Constructor
     *
     * @param ConstantAccessor[] $constants
     */
    public function __construct(array $constants = [])
    {
        $this->contants = $constants;
    }

    /**
     * Add constant from array configuration
     *
     * @param array $configuration
     * @return ConstantCollection
     */
    public function addFromArray(array $configuration)
    {
        return $this->add(new ConstantAccessor($configuration));
    }

    /**
     * Add constant accesor to collection
     *
     * @param ConstantAccessor $accessor
     * @return ConstantCollection
     */
    public function add(ConstantAccessor $accessor)
    {
        $this->contants[$accessor->getKey()] = $accessor;

        return $this;
    }

    /**
     * Transform the collection in array
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function(ConstantAccessor $accessor) {
            return $accessor->toArray();
        }, $this->contants);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->contants[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->contants[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->contants[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->contants[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contants);
    }
}
