<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.2.1|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP80Migration:risky' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'braces' => ['allow_single_line_closure' => true, 'position_after_functions_and_oop_constructs' => 'same'],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => false,
        'mb_str_functions' => true,
        'operator_linebreak' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('GPBMetadata')
        ->in('./src')
    )
    ;
