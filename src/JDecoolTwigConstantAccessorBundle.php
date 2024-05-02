<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle;

use JDecool\Bundle\TwigConstantAccessorBundle\Attribute\AsTwigConstantAccessor;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantExtensionPass;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantOptimizerPass;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JDecoolTwigConstantAccessorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerAttributeForAutoconfiguration(
            AsTwigConstantAccessor::class,
            static function(ChildDefinition $definition, AsTwigConstantAccessor $attribute, \Reflector $reflector): void {
                if (!$reflector instanceof \ReflectionClass) {
                    throw new \LogicException('AsTwigConstantAccessor attribute can only be used on classes.');
                }

                $definition->addTag(
                    'twig.constant_accessor',
                    [
                        'class' => $definition->getClass() ?? $reflector->getName(),
                        'alias' => $attribute->alias,
                        'matches' => $attribute->matches,
                    ],
                );
            },
        );

        $container->addCompilerPass(new TwigConstantExtensionPass());
        $container->addCompilerPass(new TwigConstantOptimizerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JDecoolTwigConstantAccessorExtension('twig_constant_accessor');
    }
}
