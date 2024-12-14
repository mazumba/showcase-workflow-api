<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/migrations',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->append([__FILE__])
;

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_unneeded_final_method' => false, // Risky rule: risky when child class overrides a ``private`` method.
        'concat_space' => ['spacing' => 'one'], // overrides @Symfony rule set
        'yoda_style' => false, // overrides @Symfony rule set
        'global_namespace_import' => ['import_classes' => true], // overrides @Symfony rule set
        'phpdoc_align' => ['align' => 'left'], // overrides @Symfony rule set
        'phpdoc_to_comment' => ['ignored_tags' => ['todo', 'var']], // overrides @Symfony rule set
        'native_constant_invocation' => false, // overrides @Symfony:risky rule set
        'declare_strict_types' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
    ])
    ->setFinder($finder)
;
