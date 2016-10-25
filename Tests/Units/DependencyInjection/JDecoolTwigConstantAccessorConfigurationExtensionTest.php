<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Configuration;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

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
            __DIR__ . '/../../Fixtures/config/config.yml',
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    protected function getContainerExtension()
    {
        return new JDecoolTwigConstantAccessorExtension('twig_constant_accessor');
    }

    protected function getConfiguration()
    {
        return new Configuration('twig_constant_accessor');
    }
}
