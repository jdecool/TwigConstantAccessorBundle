<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantResolver;
use JDecool\Bundle\TwigConstantAccessorBundle\Attribute\AsTwigConstant;
use JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantExtension;
use Kcs\ClassFinder\Finder\ComposerFinder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class TwigConstantAccessorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $constantExtensionDefinition = new Definition(ConstantExtension::class);
        $constantExtensionDefinition->addArgument($this->resolveConstants($config));
        $constantExtensionDefinition->addTag('twig.extension');
        $container->setDefinition(ConstantExtension::class, $constantExtensionDefinition);
    }

    private function resolveConstants(array $config): array
    {
        $finder = new ComposerFinder();
        $resolver = new ConstantResolver();

        return array_merge(
            $resolver->fromClassList($config['classes']),
            $resolver->fromClassList($finder->withAttribute(AsTwigConstant::class)),
        );
    }
}
