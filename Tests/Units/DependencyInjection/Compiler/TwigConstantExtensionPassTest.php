<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Units\DependencyInjection\Compiler;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantExtensionPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TwigConstantExtensionPassTest extends AbstractCompilerPassTestCase
{
    public function testConstantServiceRegistrationWithoutData()
    {
        $this->createContainerBuilder();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'constant_accessor.accessors',
            0,
            []
        );
    }

    public function testConstantServiceRegistrationWithoutTaggedService()
    {
        $service = (new Definition())
            ->setClass('ActivationStatus');

        $this->createContainerBuilder(['service.activation' => $service]);

        $registeredService = $this->container->findDefinition('service.activation');
        $this->assertEmpty($registeredService->getMethodCalls());
    }

    public function testConstantServiceRegistration()
    {
        $service = (new Definition())
            ->setClass('ActivationStatus')
            ->addTag('twig.constant_accessor');

        $this->createContainerBuilder(['service.activation' => $service]);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'ActivationStatus',
                    'alias'   => null,
                    'matches' => null,
                ]
            ]
        );
    }

    public function testConstantServiceRegistrationWithParameters()
    {
        $service = (new Definition())
            ->setClass('Foo\Bar')
            ->addTag('twig.constant_accessor', [
                'alias'   => 'MyClassAlias',
                'matches' => 'myregExp',
            ]);

        $this->createContainerBuilder(['service.activation' => $service]);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'constant_accessor.accessors',
            'addFromArray',
            [
                [
                    'class'   => 'Foo\Bar',
                    'alias'   => 'MyClassAlias',
                    'matches' => 'myregExp',
                ]
            ]
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TwigConstantExtensionPass());
    }

    private function createContainerBuilder(array $services = [])
    {
        $accessorsCollection = (new Definition())
            ->setClass('JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection')
            ->addArgument([]);
        $this->setDefinition('constant_accessor.accessors', $accessorsCollection);

        foreach ($services as $id => $definition) {
            $this->setDefinition($id, $definition);
        }

        $this->compile();
    }
}
