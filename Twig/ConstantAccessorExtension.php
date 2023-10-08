<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class ConstantAccessorExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var array */
    private $constants;

    /**
     * Constructor.
     */
    public function __construct(array $constants = [])
    {
        $this->constants = $constants;
    }

    public function getGlobals(): array
    {
        return $this->constants;
    }

    public function getName()
    {
        return 'twig.constant_accessor';
    }
}
