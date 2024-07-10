# TwigConstantAccessorBundle

[![Build Status](https://github.com/jdecool/TwigConstantAccessorBundle/actions/workflows/ci.yml/badge.svg)](https://actions-badge.atrox.dev/jdecool/TwigConstantAccessorBundle/goto?ref=main)
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
