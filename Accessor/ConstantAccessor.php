<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantAccessor
{
    private \ReflectionClass $reflectionClass;
    private array $options;

    /**
     * Constructor
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
     */
    public function getKey(): string
    {
        if (!empty($this->options['alias'])) {
            return $this->options['alias'];
        }

        return $this->reflectionClass->getShortName();
    }

    /**
     * Get declared matches rules
     */
    public function getMatches(): ?string
    {
        if (empty($this->options['matches'])) {
            return null;
        }

        return $this->options['matches'];
    }

    /**
     * Extract class constants
     */
    public function getConstants(): array
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
     */
    public function toArray(): array
    {
        return [
            'class'     => $this->reflectionClass->getName(),
            'alias'     => $this->getKey(),
            'matches'   => $this->getMatches(),
            'constants' => $this->getConstants(),
        ];
    }
}
