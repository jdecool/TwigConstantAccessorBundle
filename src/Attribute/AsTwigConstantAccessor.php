<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsTwigConstantAccessor
{
    public function __construct(
        public readonly ?string $alias = null,
        public readonly ?string $matches = null,
    ) {
    }
}
