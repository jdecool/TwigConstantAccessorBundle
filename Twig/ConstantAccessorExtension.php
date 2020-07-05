<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class ConstantAccessorExtension extends AbstractExtension implements GlobalsInterface
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
    public function getGlobals(): array
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
