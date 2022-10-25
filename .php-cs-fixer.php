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
        '@PHP80Migration:risky' => true,
        '@PHP82Migration' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'align_multiline_comment' => true,
        'array_syntax' => true,
        'braces' => ['position_after_control_structures'=>'same','position_after_functions_and_oop_constructs'=>'same'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => false,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'global_namespace_import' => true,
        'mb_str_functions' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'operator_linebreak' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_types_order' => true,
        'simple_to_complex_string_variable' => true,
        'simplified_if_return' => true,
        'yoda_style' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('GPBMetadata')
        ->in('./src')
    )
    ;
