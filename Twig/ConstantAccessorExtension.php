<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Twig;

use Twig_Extension;

class ConstantAccessorExtension extends Twig_Extension
{
    /** @var array */
    private $constants;


    /**
     * Constructor
     *
     * @param array $constants
     */
    public function __construct(array $constants = [])
    {
        $this->constants = $constants;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return $this->constants;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig.constant_accessor';
    }
}
