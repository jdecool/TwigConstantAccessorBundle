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
                    if (!empty($attributes['matches'])) {
                        $c = [];
                        foreach ($reflectionClass->getConstants() as $const => $value) {
                            if (false === ($matches = preg_match($attributes['matches'], $const))) {
                                throw new \InvalidArgumentException(sprintf('RegExp rule "%s" is not valid.', $attributes['matches']));
                            }

                            if ($matches) {
                                $c[$const] = $value;
                            }
                        }
                    } else {
                        $c = $reflectionClass->getConstants();
                    }

                    $name = isset($attributes['alias']) ? $attributes['alias'] : $reflectionClass->getShortName();
                    $constants[$name] = $c;
                }
            } else {
                $constants[$reflectionClass->getShortName()] = $reflectionClass->getConstants();
            }
        }

        $extension->replaceArgument(0, $constants);
    }
}
