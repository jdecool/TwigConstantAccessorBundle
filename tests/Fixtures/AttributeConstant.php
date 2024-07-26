<?php

namespace Fixtures;

use JDecool\Bundle\TwigConstantAccessorBundle\Attribute\AsTwigConstant;

#[AsTwigConstant]
class AttributeConstant
{
    public const FROM_ATTRIBUTE_1 = 'from_attribute_1';
    public const FROM_ATTRIBUTE_2 = 'from_attribute_2';
}
