<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Functionals;

use JDecool\Bundle\TwigConstantAccessorBundle\JDecoolTwigConstantAccessorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TemplateKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new JDecoolTwigConstantAccessorBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
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
                    'FooEnum',
                    'FooBackedEnum',
                    ['class' => 'ActivationStatus'],
                    ['class' => 'FooBarConstant', 'alias' => 'FooBarAlias'],
                    ['class' => 'RegExp\Rules', 'matches' => '/^RULE_/'],
                ],
            ]);
        });
    }

    public function getRootDir(): string
    {
        return realpath(__DIR__) ?: throw new \RuntimeException('Unable to find the root directory.');
    }

    public function getCacheDir(): string
    {
        return sprintf('%s/logs/cache/%s', $this->getBasePath(), $this->environment);
    }

    public function getLogDir(): string
    {
        return sprintf('%s/logs', $this->getBasePath());
    }

    public function getBasePath(): string
    {
        return sprintf('%s/%s/TemplateKernel', sys_get_temp_dir(), Kernel::VERSION);
    }
}
