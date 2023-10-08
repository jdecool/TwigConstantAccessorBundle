<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Configuration;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class JDecoolTwigConstantAccessorConfigurationExtensionTest extends AbstractExtensionConfigurationTestCase
{
    public function testLoadFileConfiguration()
    {
        $expectedConfiguration = [
            'classes' => [
                ['class' => 'ActivationStatus', 'alias' => null, 'matches' => null],
                ['class' => 'FooBarConstant', 'alias' => null, 'matches' => null],
                ['class' => 'Foo\Bar', 'alias' => null, 'matches' => null],
                ['class' => 'Foo\Bar', 'alias' => 'StatusAlias', 'matches' => null],
                ['class' => 'RegExp\Rules', 'alias' => null, 'matches' => '/^RULE_/'],
            ],
        ];

        $sources = [
            __DIR__.'/../../Fixtures/config/config.yml',
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    protected function getContainerExtension(): ExtensionInterface
    {
        return new JDecoolTwigConstantAccessorExtension('twig_constant_accessor');
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration('twig_constant_accessor');
    }
}
