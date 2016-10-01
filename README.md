# TwigConstantAccessorBundle

[![Build Status](https://travis-ci.org/jdecool/TwigConstantAccessorBundle.svg?branch=master)](https://travis-ci.org/jdecool/TwigConstantAccessorBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jdecool/TwigConstantAccessorBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jdecool/TwigConstantAccessorBundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/jdecool/twig-constant-accessor-bundle/v/stable.png)](https://packagist.org/packages/jdecool/twig-constant-accessor-bundle)

This bundle simplify access of your class constants in Twig.

## Install it

Install extension using [composer](https://getcomposer.org):

```json
{
    "require": {
        "jdecool/twig-constant-accessor-bundle": "~1.0"
    }
}
```

Enable the extension in your application `AppKernel`:

```php
<?php

public function registerBundles()
{
    $bundles = [
        // ...
        new JDecool\Bundle\TwigConstantAccessorBundle\JDecoolTwigConstantAccessorBundle(),
    ];

    // ...

    return $bundles;
}
```

Register the class you want to access constant in your configuration file :

```yaml
twig_constant_accessor:
    classes:
        - AppBundle\Model\Foo
        - { class: 'AppBundle\Model\Bar' }
        - { class: 'AppBundle\Model\FooBar', alias: 'FooBarAlias' }
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
```

After you can access your class constant in your templates :

```twig
{{ ServiceClass.MY_CONSTANT }}
{{ MyClassAlias.KEY }}

{% if 'value' == ServiceClass.My_CONSTANT %}Test is OK{% endif %}
```
