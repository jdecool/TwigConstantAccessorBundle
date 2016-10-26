<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantAccessor;
use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
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

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }
}
