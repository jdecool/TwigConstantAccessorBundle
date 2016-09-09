<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantExtensionPass;
use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\JDecoolTwigConstantAccessorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JDecoolTwigConstantAccessorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigConstantExtensionPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new JDecoolTwigConstantAccessorExtension('twig_constant_accessor');
    }
}
