<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\Twig;

use atoum;

class ConstantAccessorExtension extends atoum
{
    public function testGetGlobals()
    {
        $this
            ->given($consts = [
                'foo' => [],
                'bar' => ['name' => 'John Doe'],
            ])
            ->if($this->newTestedInstance($consts))
            ->then
                ->array($this->testedInstance->getGlobals())
                    ->isEqualTo($consts)
        ;
    }
}
