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

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension && null !== ($extension = $this->createContainerExtension())) {
            $this->extension = $extension;
        }

        if ($this->extension) {
            return $this->extension;
        }
    }
}
