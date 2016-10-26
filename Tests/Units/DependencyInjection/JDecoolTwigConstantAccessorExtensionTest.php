<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class JDecoolTwigConstantAccessorExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadEmpty()
    {
        $this->setParameter('kernel.debug', true);
        $this->load();

        $this->assertContainerBuilderHasService('twig.extension.constant_accessor');
    }

    public function testLoadWithClassStringDefinition()
    {
        $this->setParameter('kernel.debug', true);
        $this->load([
            'classes' => [
                'ActivationStatus',
            ],
        ]);

        $this->assertContainerBuilderHasService('constant_accessor.accessors');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => null,
                    'matches' => null,
                ],
            ]
        );
    }

    public function testLoadWithClassArrayDefinition()
    {
        $this->setParameter('kernel.debug', true);
        $this->load([
            'classes' => [
                ['class' => 'ActivationStatus'],
            ],
        ]);

        $this->assertContainerBuilderHasService('constant_accessor.accessors');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => null,
                    'matches' => null,
                ],
            ]
        );
    }

    public function testLoadWithClassAliasDefinition()
    {
        $this->setParameter('kernel.debug', true);
        $this->load([
            'classes' => [
                ['class' => 'ActivationStatus', 'alias' => 'AliasName'],
            ],
        ]);

        $this->assertContainerBuilderHasService('constant_accessor.accessors');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => 'AliasName',
                    'matches' => null,
                ],
            ]
        );
    }

    public function testLoadWithMultipleClassDefinition()
    {
        $this->setParameter('kernel.debug', true);
        $this->load([
            'classes' => [
                'ActivationStatus',
                ['class' => 'FooBarConstant'],
                ['class' => 'ActivationStatus', 'alias' => 'StatusAlias'],
            ],
        ]);

        $this->assertContainerBuilderHasService('constant_accessor.accessors');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => null,
                    'matches' => null,
                ],
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'FooBarConstant',
                    'alias'   => null,
                    'matches' => null,
                ],
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => 'StatusAlias',
                    'matches' => null,
                ],
            ]
        );
    }

    public function testLoadWithMatchesDefinition()
    {
        $this->setParameter('kernel.debug', true);
        $this->load([
            'classes' => [
                ['class' => 'FooBarConstant', 'matches' => '/^RULE/'],
            ],
        ]);

        $this->assertContainerBuilderHasService('constant_accessor.accessors');
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'FooBarConstant',
                    'alias'   => null,
                    'matches' => '/^RULE/',
                ],
            ]
        );
    }

    protected function getContainerExtensions()
    {
        return [
            new JDecoolTwigConstantAccessorExtension('twig_constant_accessor'),
        ];
    }
}
