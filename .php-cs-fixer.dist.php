<?php

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'function_declaration' => ['closure_function_spacing' => 'none'],
    ])
    ->setFinder($finder)
;
