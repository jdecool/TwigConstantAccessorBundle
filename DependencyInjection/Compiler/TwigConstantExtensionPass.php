<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigConstantExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('twig.extension.constant_accessor')) {
            return;
        }

        $extension = $container->getDefinition('twig.extension.constant_accessor');
        $constants = $extension->getArgument(0);

        $taggedServices = $container->findTaggedServiceIds('twig.constant_accessor');
        foreach ($taggedServices as $id => $tags) {
            $service = $container->getDefinition($id);

            $reflectionClass = new \ReflectionClass($service->getClass());
            if (!empty($tags)) {
                foreach ($tags as $attributes) {
                    $name = isset($attributes['alias']) ? $attributes['alias'] : $reflectionClass->getShortName();
                    $constants[$name] = $reflectionClass->getConstants();
                }
            } else {
                $constants[$reflectionClass->getShortName()] = $reflectionClass->getConstants();
            }
        }

        $extension->replaceArgument(0, $constants);
    }
}
