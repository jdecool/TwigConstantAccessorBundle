<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('twig_constant_accessor');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(static fn ($v) => ['class' => $v])
                        ->end()
                        ->treatNullLike([])
                        ->treatFalseLike([])
                        ->children()
                            ->scalarNode('class')->defaultNull()->end()
                            ->scalarNode('alias')->defaultNull()->end()
                            ->scalarNode('matches')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
