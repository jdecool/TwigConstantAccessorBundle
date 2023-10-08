<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class ConstantAccessorExtension extends AbstractExtension implements GlobalsInterface
{
    private array $constants;

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

    public function getName(): string
    {
        return 'twig.constant_accessor';
    }
}
