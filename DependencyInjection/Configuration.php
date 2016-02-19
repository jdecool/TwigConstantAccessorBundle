<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('twig_constant_accessor');

        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($v) { return ['class' => $v]; })
                        ->end()
                        ->treatNullLike([])
                        ->treatFalseLike([])
                        ->children()
                            ->scalarNode('class')->defaultNull()->end()
                            ->scalarNode('alias')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
