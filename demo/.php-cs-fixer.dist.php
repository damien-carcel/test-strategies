<?php

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
    ->exclude('var');

return new PhpCsFixer\Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
