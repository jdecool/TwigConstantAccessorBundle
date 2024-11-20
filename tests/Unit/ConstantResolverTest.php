<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Tests\Unit;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantResolver;
use PHPUnit\Framework\TestCase;

final class ConstantResolverTest extends TestCase
{
    private ConstantResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new ConstantResolver();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->resolver);
    }

    public function testResolveConstantsFromInterface(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => ConstantInterface::class],
        ]);

        static::assertEquals([
            'ConstantInterface' => [
                'VALUE' => 'value',
            ],
        ], $constants);
    }

    public function testResolveConstantsFromBackedEnum(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => MyEnum::class],
        ]);

        static::assertEquals([
            'MyEnum' => [
                'Foo' => 'foo',
                'Bar' => 'bar',
            ],
        ], $constants);
    }

    public function testResolveConstantsFromClass(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => Foo::class],
        ]);

        static::assertEquals([
            'Foo' => [
                'FOO_VALUE1' => 'foo_value1',
                'FOO_VALUE2' => 'foo_value2',
                'FOO_VALUE3' => 'foo_value3',
            ],
        ], $constants);
    }

    public function testResolveConstantsExtractPublicValuesByDefault(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => Bar::class],
        ]);

        static::assertEquals([
            'Bar' => [
                'BAR_VALUE1' => 'bar_value1',
            ],
        ], $constants);
    }

    public function testResolveConstantsUsingAlias(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => Foo::class, 'alias' => 'FooAlias'],
        ]);

        static::assertEquals([
            'FooAlias' => [
                'FOO_VALUE1' => 'foo_value1',
                'FOO_VALUE2' => 'foo_value2',
                'FOO_VALUE3' => 'foo_value3',
            ],
        ], $constants);
    }

    public function testResolveConstantsUsingRegexp(): void
    {
        $constants = $this->resolver->fromClassList([
            ['class' => Foo::class, 'matches' => '/FOO_VALUE(1|3)/'],
        ]);

        static::assertEquals([
            'Foo' => [
                'FOO_VALUE1' => 'foo_value1',
                'FOO_VALUE3' => 'foo_value3',
            ],
        ], $constants);
    }
}

class Foo
{
    public const FOO_VALUE1 = 'foo_value1';
    public const FOO_VALUE2 = 'foo_value2';
    public const FOO_VALUE3 = 'foo_value3';
}

class Bar
{
    public const BAR_VALUE1 = 'bar_value1';
    protected const BAR_VALUE2 = 'bar_value2';

    /** @var string @phpstan-ignore-next-line */
    private const BAR_VALUE3 = 'bar_value3';
}

interface ConstantInterface
{
    public const VALUE = 'value';
}

enum MyEnum: string
{
    case Foo = 'foo';
    case Bar = 'bar';
}
