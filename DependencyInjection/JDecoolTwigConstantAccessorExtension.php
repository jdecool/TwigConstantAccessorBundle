<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class JDecoolTwigConstantAccessorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $constantDefinition = [];
        foreach ($config['classes'] as $class) {
            $reflectionClass = new \ReflectionClass($class['class']);

            $name = !empty($class['alias']) ? $class['alias'] : $reflectionClass->getShortName();
            $constantDefinition[$name] = $reflectionClass->getConstants();
        }

        $extension = $container->getDefinition('twig.extension.constant_accessor');
        $extension->replaceArgument(0, $constantDefinition);
    }
}
