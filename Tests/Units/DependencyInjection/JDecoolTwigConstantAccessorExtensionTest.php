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

        $this->assertContainerBuilderHasService('twig.extension.constant_accessor');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            [
                'ActivationStatus' => [
                    'INACTIVE' => 'activationstatus_inactive',
                    'ACTIVE'   => 'activationstatus_active',
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

        $this->assertContainerBuilderHasService('twig.extension.constant_accessor');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            [
                'ActivationStatus' => [
                    'INACTIVE' => 'activationstatus_inactive',
                    'ACTIVE'   => 'activationstatus_active',
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

        $this->assertContainerBuilderHasService('twig.extension.constant_accessor');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            [
                'AliasName' => [
                    'INACTIVE' => 'activationstatus_inactive',
                    'ACTIVE'   => 'activationstatus_active',
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

        $this->assertContainerBuilderHasService('twig.extension.constant_accessor');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            [
                'ActivationStatus' => [
                    'INACTIVE' => 'activationstatus_inactive',
                    'ACTIVE'   => 'activationstatus_active',
                ],
                'FooBarConstant' => [
                    'FOO' => 'foobarconstant_foo',
                    'BAR' => 'foobarconstant_bar',
                ],
                'StatusAlias' => [
                    'INACTIVE' => 'activationstatus_inactive',
                    'ACTIVE'   => 'activationstatus_active',
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
