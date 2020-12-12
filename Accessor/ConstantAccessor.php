<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantAccessor
{
    /** @var \ReflectionClass */
    private $reflectionClass;

    /** @var array */
    private $options;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (empty($options['class'])) {
            throw new \InvalidArgumentException(sprintf('Missing "class" name.'));
        }

        $this->reflectionClass = new \ReflectionClass($options['class']);
        $this->options = $options;
    }

    /**
     * Get constant access key
     *
     * @return string
     */
    public function getKey()
    {
        if (!empty($this->options['alias'])) {
            return $this->options['alias'];
        }

        return $this->reflectionClass->getShortName();
    }

    /**
     * Get declared matches rules
     *
     * @return string
     */
    public function getMatches()
    {
        if (empty($this->options['matches'])) {
            return null;
        }

        return $this->options['matches'];
    }

    /**
     * Extract class constants
     *
     * @return array
     */
    public function getConstants()
    {
        if (empty($this->options['matches'])) {
            return $this->reflectionClass->getConstants();
        }

        $constants = [];
        foreach ($this->reflectionClass->getConstants() as $const => $value) {
            if (false === ($matches = preg_match($this->options['matches'], $const))) {
                throw new \InvalidArgumentException(sprintf('RegExp rule "%s" is not valid.', $this->options['matches']));
            }

            if ($matches) {
                $constants[$const] = $value;
            }
        }

        return $constants;
    }

    /**
     * Transform object to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'class'     => $this->reflectionClass->getName(),
            'alias'     => $this->getKey(),
            'matches'   => $this->getMatches(),
            'constants' => $this->getConstants(),
        ];
    }
}
