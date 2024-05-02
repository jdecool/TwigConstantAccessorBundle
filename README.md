# TwigConstantAccessorBundle

[![Build Status](https://travis-ci.org/jdecool/TwigConstantAccessorBundle.svg?branch=master)](https://travis-ci.org/jdecool/TwigConstantAccessorBundle?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jdecool/twig-constant-accessor-bundle/v/stable.png)](https://packagist.org/packages/jdecool/twig-constant-accessor-bundle)

This bundle simplify access of your enum values or class constants in Twig.

## Install it

Install extension using [composer](https://getcomposer.org):

```bash
$ composer require jdecool/twig-constant-accessor-bundle
```

If you don't use Symfony Flex, you have to enabled the bundle in your `config/bundles.php` configuration:

```php
<?php

return [
    // ...
    JDecool\Bundle\TwigConstantAccessorBundle\JDecoolTwigConstantAccessorBundle::class => ['all' => true],
];
```

Register the class you want to access constant in your configuration file :

```yaml
twig_constant_accessor:
    classes:
        - AppBundle\Model\Foo
        - { class: 'AppBundle\Model\Bar' }
        - { class: 'AppBundle\Model\FooBar', alias: 'FooBarAlias' }
        - { class: 'AppBundle\Model\ConstantClass', matches: '/^RULE_/' } # matches accept an regexp compatible with the preg_match function
```

You can also register a class in your container configuration using the `twig.constant_accessor` tag :

```yaml
services:
    my_service:
        class: Namespace\To\ServiceClass
        tags:
            - { name: twig.constant_accessor }

    my_collection:
        class: MyClass
        tags:
            - { name: twig.constant_accessor, alias: 'MyClassAlias' }

    filtered_constants:
        class: ConstantsClass
        tags:
            - { name: twig.constant_accessor, matches: '/^RULE_/' } # matches accept an regexp compatible with the preg_match function
```

Or you can use the `#[AsTwigConstantAccessor]` attribute on your class :

```php
use JDecool\Bundle\TwigConstantAccessorBundle\Annotation\AsTwigConstantAccessor;

#[AsTwigConstantAccessor]
class MyClass
{
    // ...
}
```

After you can access your class constant in your templates :

```twig
{{ ServiceClass.MY_CONSTANT }}
{{ MyClassAlias.KEY }}

{% if 'value' == ServiceClass.My_CONSTANT %}Test is OK{% endif %}
```
