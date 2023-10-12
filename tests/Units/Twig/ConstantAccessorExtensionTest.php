<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\Twig;

use JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension;
use PHPUnit\Framework\TestCase;

class ConstantAccessorExtensionTest extends TestCase
{
    public function testGlobalsAccess(): void
    {
        $expected = [
            'foo' => [],
            'bar' => ['name' => 'John Doe'],
        ];

        $extension = new ConstantAccessorExtension($expected);
        $this->assertEquals($expected, $extension->getGlobals());
    }
}
