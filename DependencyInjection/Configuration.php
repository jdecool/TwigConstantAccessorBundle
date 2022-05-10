<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private string $alias;

    /**
     * Constructor
     */
    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->alias);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v) {
                                return ['class' => $v];
                            })
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
