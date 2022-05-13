<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Functionals;

use JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Loader\FilesystemLoader;

class TemplateKernelTest extends \PHPUnit_Framework_TestCase
{
    private $kernel;

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

    public function testStringConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('foo_bar_name', $content);
    }

    public function testArrayConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('activationstatus_inactive', $content);
        $this->assertStringContainsString('activationstatus_active', $content);
    }

    public function testAliasConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertStringContainsString('foobarconstant_foo', $content);
        $this->assertStringContainsString('foobarconstant_bar', $content);
    }

    public function testRegExpConfiguration()
    {
        $content = $this->render('regexp.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringNotContainsString('john', $content);
        $this->assertStringNotContainsString('doe', $content);
    }

    public function testExceptionThrowWhenUsingVariableDontMatchRegExp()
    {
        $this->expectException(RuntimeError::class);

        $content = $this->render('regexp_not_existant_vars.html.twig');
    }

    public function testServiceConfiguration()
    {
        $content = $this->render('regexp_service_alias.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringContainsString('john', $content);
        $this->assertStringContainsString('doe', $content);
    }

    public function testServiceMatchesConfiguration()
    {
        $content = $this->render('regexp_service.html.twig');

        $this->assertStringContainsString('foo', $content);
        $this->assertStringContainsString('bar', $content);

        $this->assertStringNotContainsString('john', $content);
        $this->assertStringNotContainsString('doe', $content);
    }

    private function render($template)
    {
        return $this->kernel->getContainer()->get('template')->render($template);
    }
}
