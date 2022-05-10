<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantAccessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigConstantOptimizerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('constant_accessor.accessors') || !$container->has('twig.extension.constant_accessor')) {
            return;
        }

        $flattenConstants = [];

        /** @var ConstantAccessor[] $accessorsCollection */
        $accessorsCollection = $container->get('constant_accessor.accessors');
        foreach ($accessorsCollection as $accessor) {
            $flattenConstants[$accessor->getKey()] = $accessor->getConstants();
        }

        $twigExtensionDefinition = $container->getDefinition('twig.extension.constant_accessor');
        $twigExtensionDefinition->replaceArgument(0, $flattenConstants);
    }
}
