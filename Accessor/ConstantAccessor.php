<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

class ConstantAccessor extends \ReflectionClass
{
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

        $this->options = $options;

        parent::__construct($options['class']);
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

        return $this->getShortName();
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
            return parent::getConstants();
        }

        $constants = [];
        foreach (parent::getConstants() as $const => $value) {
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
            'class'     => $this->getName(),
            'alias'     => $this->getKey(),
            'matches'   => $this->getMatches(),
            'constants' => $this->getConstants(),
        ];
    }
}
