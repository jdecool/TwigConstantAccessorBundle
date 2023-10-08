<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantExtensionPass;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantOptimizerPass;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JDecoolTwigConstantAccessorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TwigConstantExtensionPass());
        $container->addCompilerPass(new TwigConstantOptimizerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JDecoolTwigConstantAccessorExtension('twig_constant_accessor');
    }
}
