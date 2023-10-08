<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class JDecoolTwigConstantAccessorExtension extends ConfigurableExtension
{
    private string $alias;

    /**
     * Constructor.
     */
    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration($this->alias);
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $accessorCollectionDefinition = new Definition('JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection');
        $accessorCollectionDefinition->setPublic(false);
        foreach ($mergedConfig['classes'] as $class) {
            $accessorCollectionDefinition->addMethodCall('addFromArray', [$class]);
        }

        $container->setDefinition('constant_accessor.accessors', $accessorCollectionDefinition);

        if ($container->getParameter('kernel.debug')) {
            $loader->load('debug.yml');
        }
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
