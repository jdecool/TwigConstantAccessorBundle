<?php

namespace Foo;

use JDecool\Bundle\TwigConstantAccessorBundle\Attribute\AsTwigConstantAccessor;

#[AsTwigConstantAccessor]
class ClassConstantByAttribute
{
    public const FOO = 'foo';
    public const BAR = 'bar';
}
