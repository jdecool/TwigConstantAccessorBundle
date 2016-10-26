<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Functionals;

use Symfony\Component\Filesystem\Filesystem;

class TemplateKernelTest extends \PHPUnit_Framework_TestCase
{
    private $kernel;

    protected function setUp()
    {
        $this->kernel = new TemplateKernel('dev', true);
        if (file_exists($this->kernel->getBasePath())) {
            $fs = new Filesystem();
            $fs->remove($this->kernel->getBasePath());
        }

        $this->kernel->boot();
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->kernel->getBasePath());
    }

    public function testStringConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertContains('foo_bar_name', $content);
    }

    public function testArrayConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertContains('activationstatus_inactive', $content);
        $this->assertContains('activationstatus_active', $content);
    }

    public function testAliasConfiguration()
    {
        $content = $this->render('index.html.twig');

        $this->assertContains('foobarconstant_foo', $content);
        $this->assertContains('foobarconstant_bar', $content);
    }

    public function testRegExpConfiguration()
    {
        $content = $this->render('regexp.html.twig');

        $this->assertContains('foo', $content);
        $this->assertContains('bar', $content);

        $this->assertNotContains('john', $content);
        $this->assertNotContains('doe', $content);
    }

    public function testServiceConfiguration()
    {
        $content = $this->render('regexp_service_alias.html.twig');

        $this->assertContains('foo', $content);
        $this->assertContains('bar', $content);

        $this->assertContains('john', $content);
        $this->assertContains('doe', $content);
    }

    public function testServiceMatchesConfiguration()
    {
        $content = $this->render('regexp_service.html.twig');

        $this->assertContains('foo', $content);
        $this->assertContains('bar', $content);

        $this->assertNotContains('john', $content);
        $this->assertNotContains('doe', $content);
    }

    private function render($template)
    {
        return $this->kernel->getContainer()->get('twig')->render($template);
    }
}
