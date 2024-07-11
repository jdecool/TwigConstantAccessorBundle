<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class ConstantExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly array $constants,
    ) {
    }

    public function getGlobals(): array
    {
        return $this->constants;
    }
}
