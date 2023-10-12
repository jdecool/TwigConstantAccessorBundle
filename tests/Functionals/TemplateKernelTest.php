<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Functionals;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Error\RuntimeError;

class TemplateKernelTest extends TestCase
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

    public function testStringConfiguration(): void
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('foo_bar_name', $content);
    }

    public function testArrayConfiguration(): void
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('activationstatus_inactive', $content);
        $this->assertStringContainsString('activationstatus_active', $content);
    }

    public function testAliasConfiguration(): void
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('foobarconstant_foo', $content);
        $this->assertStringContainsString('foobarconstant_bar', $content);
    }

    public function testRegExpConfiguration(): void
    {
        $content = $this->render('regexp.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringNotContainsString('john', $content);
        $this->assertStringNotContainsString('doe', $content);
    }

    public function testExceptionThrowWhenUsingVariableDontMatchRegExp(): void
    {
        $this->expectException(RuntimeError::class);

        $content = $this->render('regexp_not_existant_vars.html.twig');
    }

    public function testServiceConfiguration(): void
    {
        $content = $this->render('regexp_service_alias.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringContainsString('john', $content);
        $this->assertStringContainsString('doe', $content);
    }

    public function testServiceMatchesConfiguration(): void
    {
        $content = $this->render('regexp_service.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringNotContainsString('john', $content);
        $this->assertStringNotContainsString('doe', $content);
    }

    private function render(string $template): string
    {
        /** @var Environment $twig */
        $twig = $this->kernel->getContainer()->get('template');

        return $twig->render($template);
    }
}
