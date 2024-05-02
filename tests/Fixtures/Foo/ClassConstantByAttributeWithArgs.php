<?php

namespace Foo;

use JDecool\Bundle\TwigConstantAccessorBundle\Attribute\AsTwigConstantAccessor;

#[AsTwigConstantAccessor(
    alias: 'WithAttribute',
    matches: '/^SELECTED_/',
)]
class ClassConstantByAttributeWithArgs
{
    public const FOO = 'foo';
    public const BAR = 'bar';

    public const SELECTED_FOO = 'selected_foo';
    public const SELECTED_BAR = 'selected_bar';
}
