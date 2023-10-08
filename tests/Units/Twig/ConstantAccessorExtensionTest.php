<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\Twig;

use JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension;

class ConstantAccessorExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testGlobalsAccess()
    {
        $expected = [
            'foo' => [],
            'bar' => ['name' => 'John Doe'],
        ];

        $extension = new ConstantAccessorExtension($expected);
        $this->assertEquals($expected, $extension->getGlobals());
    }
}
