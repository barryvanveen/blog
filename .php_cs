<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'bootstrap/cache',
        'node_modules',
        'storage',
        'vendor',
    ])
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_helper.php')
    ->in(__DIR__);
;

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
    ))
    ->setFinder($finder)
;