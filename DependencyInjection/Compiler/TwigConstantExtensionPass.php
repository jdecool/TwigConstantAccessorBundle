<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigConstantExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('constant_accessor.accessors')) {
            return;
        }

        $accessorCollectionDefinition = $container->getDefinition('constant_accessor.accessors');

        $taggedServices = $container->findTaggedServiceIds('twig.constant_accessor');
        foreach ($taggedServices as $id => $tags) {
            $service = $container->getDefinition($id);

            if (!empty($tags)) {
                foreach ($tags as $attributes) {
                    $attributes = array_merge([
                        'class'   => $service->getClass(),
                        'alias'   => null,
                        'matches' => null,
                    ], $attributes);

                    $accessorCollectionDefinition->addMethodCall('addFromArray', [$attributes]);
                }
            }
        }
    }
}
