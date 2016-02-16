<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle;

use JDecool\Bundle\TwigConstantAccessorBundle\DependencyInjection\Compiler\TwigConstantExtensionPass;
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
}
