<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection\Compiler;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantAccessor;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantOptimizerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TwigConstantOptimizerPassTest extends AbstractCompilerPassTestCase
{
    public function testOptimizationWithoutData(): void
    {
        $this->createContainerBuilder();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            []
        );
    }

    public function testOptimization(): void
    {
        $fooClass = $this->createMock(ConstantAccessor::class);
        $fooClass->method('getKey')
            ->willReturn('Foo');
        $fooClass->method('getConstants')
            ->willReturn([
                'FOO_1' => 'foo1',
                'FOO_2' => 'foo2',
            ]);

        $barClass = $this->getMockBuilder('JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantAccessor')
            ->disableOriginalConstructor()
            ->getMock();
        $barClass->method('getKey')
            ->willReturn('BarAlias');
        $barClass->method('getConstants')
            ->willReturn([
                'BAR_1' => 'bar1',
                'BAR_2' => 'bar2',
            ]);

        $this->createContainerBuilder([$fooClass, $barClass]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'twig.extension.constant_accessor',
            0,
            [
                'Foo' => [
                    'FOO_1' => 'foo1',
                    'FOO_2' => 'foo2',
                ],
                'BarAlias' => [
                    'BAR_1' => 'bar1',
                    'BAR_2' => 'bar2',
                ],
            ]
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigConstantOptimizerPass());
    }

    private function createContainerBuilder(array $accessors = []): void
    {
        $managerDefinition = (new Definition())
            ->setClass('JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension')
            ->addArgument([]);

        $accessorsCollection = (new Definition())
            ->setClass('JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection')
            ->addArgument($accessors);

        $this->setDefinition('twig.extension.constant_accessor', $managerDefinition);
        $this->setDefinition('constant_accessor.accessors', $accessorsCollection);
        $this->compile();
    }
}
