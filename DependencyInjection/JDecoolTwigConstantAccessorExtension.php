<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

class JDecoolTwigConstantAccessorExtension extends ConfigurableExtension
{
    /** @var string */
    private $alias;

    /**
     * Constructor
     *
     * @param string $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->alias);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.yml');
        }

        $constantDefinition = [];
        foreach ($mergedConfig['classes'] as $class) {
            $reflectionClass = new \ReflectionClass($class['class']);

            if (null !== $class['matches']) {
                $constants = [];
                foreach ($reflectionClass->getConstants() as $const => $value) {
                    if (false === ($matches = preg_match($class['matches'], $const))) {
                        throw new \InvalidArgumentException(sprintf('RegExp rule "%s" is not valid.', $class['matches']));
                    }

                    if ($matches) {
                        $constants[$const] = $value;
                    }
                }
            } else {
                $constants = $reflectionClass->getConstants();
            }

            $name = !empty($class['alias']) ? $class['alias'] : $reflectionClass->getShortName();
            $constantDefinition[$name] = $constants;
        }

        $extension = $container->getDefinition('twig.extension.constant_accessor');
        $extension->replaceArgument(0, $constantDefinition);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
