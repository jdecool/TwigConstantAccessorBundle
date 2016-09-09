<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection;

use atoum;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class JDecoolTwigConstantAccessorExtension extends atoum
{
    public function testLoad()
    {
        $this
            ->given($container = new ContainerBuilder())
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load([], $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()

            ->given($container = new ContainerBuilder())
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load(['twig_constant_accessor' => [
                'classes' => [
                    'ActivationStatus'
                ]
            ]], $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()
                ->given($definition = $container->getDefinition('twig.extension.constant_accessor'))
                ->array($definition->getArgument(0))
                    ->array['ActivationStatus']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->hasSize(1)

            ->given($container = new ContainerBuilder())
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load(['twig_constant_accessor' => [
                'classes' => [
                    ['class' => 'ActivationStatus']
                ]
            ]], $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()
                ->given($definition = $container->getDefinition('twig.extension.constant_accessor'))
                ->array($definition->getArgument(0))
                    ->array['ActivationStatus']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->hasSize(1)

            ->given($container = new ContainerBuilder())
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load(['twig_constant_accessor' => [
                'classes' => [
                    ['class' => 'ActivationStatus', 'alias' => 'AliasName']
                ]
            ]], $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()
                ->given($definition = $container->getDefinition('twig.extension.constant_accessor'))
                ->array($definition->getArgument(0))
                    ->array['AliasName']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->hasSize(1)

            ->given($container = new ContainerBuilder())
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load(['twig_constant_accessor' => [
                'classes' => [
                    'ActivationStatus',
                    ['class' => 'FooBarConstant'],
                    ['class' => 'ActivationStatus', 'alias' => 'StatusAlias']
                ]
            ]], $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()
                ->given($definition = $container->getDefinition('twig.extension.constant_accessor'))
                ->array($definition->getArgument(0))
                    ->array['ActivationStatus']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->array['FooBarConstant']
                        ->isEqualTo(['FOO' => 'foo', 'BAR' => 'bar'])
                    ->array['StatusAlias']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->hasSize(3)
        ;
    }

    public function testLoadByFile()
    {
        $this
            ->given(
                $container = new ContainerBuilder(),
                $config = <<<YAML
twig_constant_accessor:
    classes:
        - 'ActivationStatus'
        - { class: 'FooBarConstant' }
        - { class: 'Foo\Bar' }
        - { class: 'Foo\Bar', alias: 'StatusAlias' }
YAML
            )
            ->if($extension = $this->newTestedInstance('twig_constant_accessor'))
            ->and($extension->load(Yaml::parse($config), $container))
            ->then
                ->boolean($container->hasDefinition('twig.extension.constant_accessor'))
                    ->isTrue()
                ->given($definition = $container->getDefinition('twig.extension.constant_accessor'))
                ->array($definition->getArgument(0))
                    ->array['ActivationStatus']
                        ->isEqualTo(['INACTIVE' => 0, 'ACTIVE' => 1])
                    ->array['FooBarConstant']
                        ->isEqualTo(['FOO' => 'foo', 'BAR' => 'bar'])
                    ->array['Bar']
                        ->isEqualTo(['NAME' => 'name'])
                    ->array['StatusAlias']
                        ->isEqualTo(['NAME' => 'name'])
                    ->hasSize(4)
        ;
    }
}
