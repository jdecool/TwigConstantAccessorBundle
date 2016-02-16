<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection\Compiler;

use atoum;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigConstantExtensionPass extends atoum
{
    public function testProcess()
    {
        $this
            ->given(
                $container = new ContainerBuilder(),
                $container
                    ->register('twig.extension.constant_accessor', 'JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                    ->addArgument([]),
                $container
                    ->register('foo', 'ActivationStatus')
                    ->addTag('twig.constant_accessor')
            )
            ->if($this->process($container))
            ->then
                ->given($twigExtension = $container->get('twig.extension.constant_accessor'))
                ->object($twigExtension)
                    ->isInstanceOf('JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                ->array($twigExtension->getGlobals())
                    ->isEqualTo(['ActivationStatus' => ['INACTIVE' => 0, 'ACTIVE' => 1]])

            ->given(
                $container = new ContainerBuilder(),
                $container
                    ->register('twig.extension.constant_accessor', 'JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                    ->addArgument([]),
                $container
                    ->register('foo', 'ActivationStatus')
                    ->addTag('twig.constant_accessor', ['alias' => 'ClAlias'])
            )
            ->if($this->process($container))
            ->then
                ->given($twigExtension = $container->get('twig.extension.constant_accessor'))
                ->object($twigExtension)
                    ->isInstanceOf('JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                ->array($twigExtension->getGlobals())
                    ->isEqualTo(['ClAlias' => ['INACTIVE' => 0, 'ACTIVE' => 1]])

            ->given(
                $container = new ContainerBuilder(),
                $container
                    ->register('twig.extension.constant_accessor', 'JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                    ->addArgument([]),
                $container
                    ->register('foo.bar', 'FooBarConstant')
                    ->addTag('twig.constant_accessor')
                    ->addTag('twig.constant_accessor', ['alias' => 'CustomAlias'])
            )
            ->if($this->process($container))
            ->then
                ->given($twigExtension = $container->get('twig.extension.constant_accessor'))
                ->object($twigExtension)
                    ->isInstanceOf('JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
                ->array($twigExtension->getGlobals())
                    ->isEqualTo([
                        'FooBarConstant' => ['FOO' => 'foo', 'BAR' => 'bar'],
                        'CustomAlias' => ['FOO' => 'foo', 'BAR' => 'bar'],
                    ])
        ;
    }

    protected function process(ContainerBuilder $container)
    {
        $testedClass = $this->newTestedInstance();
        $testedClass->process($container);

        return true;
    }
}
