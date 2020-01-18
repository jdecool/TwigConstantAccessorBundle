<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Functionals;

use JDecool\Bundle\TwigConstantAccessorBundle\JDecoolTwigConstantAccessorBundle;
use JDecool\Bundle\TwigConstantAccessorBundle\Tests\Fixtures\TestBundle\TestBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TemplateKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new JDecoolTwigConstantAccessorBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/Resources/config/services.yml');

        $loader->load(function(ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'secret' => '$ecret',
            ]);

            $container->loadFromExtension('twig', [
                'paths' => [
                    realpath(__DIR__.'/Resources/views'),
                ],
                'strict_variables' => true,
            ]);

            $container->loadFromExtension('twig_constant_accessor', [
                'classes' => [
                    'Foo\Bar',
                    ['class' => 'ActivationStatus'],
                    ['class' => 'FooBarConstant', 'alias' => 'FooBarAlias'],
                    ['class' => 'RegExp\Rules', 'matches' => '/^RULE_/'],
                ]
            ]);
        });
    }

    public function getRootDir()
    {
        return realpath(__DIR__);
    }

    public function getCacheDir()
    {
        return sprintf('%s/logs/cache/%s', $this->getBasePath(), $this->environment);
    }

    public function getLogDir()
    {
        return sprintf('%s/logs', $this->getBasePath());
    }

    public function getBasePath()
    {
        return sprintf('%s/%s/TemplateKernel', sys_get_temp_dir(), Kernel::VERSION);
    }
}
