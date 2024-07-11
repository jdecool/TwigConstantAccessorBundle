<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Fixtures;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

final class ConstantAccessFunctionalTest extends TestCase
{
    private TemplateKernel $kernel;

    protected function setUp(): void
    {
        $this->kernel = new TemplateKernel('dev', true);
        if (file_exists($this->kernel->getBasePath())) {
            $fs = new Filesystem();
            $fs->remove($this->kernel->getBasePath());
        }

        $this->kernel->boot();
    }

    protected function tearDown(): void
    {
        $fs = new Filesystem();
        $fs->remove($this->kernel->getBasePath());
    }

    public function testConstantsAreAccessibleInView(): void
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('my_constant.my_constant_value', $content);
    }

    private function render(string $template): string
    {
        /** @var Environment $twig */
        $twig = $this->kernel->getContainer()->get('template');

        return $twig->render($template);
    }
}
