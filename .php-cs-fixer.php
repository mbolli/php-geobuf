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
	'@PHP84Migration' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@Symfony' => true,
	'@Symfony:risky' => true,
	'braces_position' => ['classes_opening_brace' => 'same_line', 'functions_opening_brace' => 'same_line'],
        'concat_space' => ['spacing' => 'one'],
        'control_structure_continuation_position' => ['position' => 'same_line'],
        'declare_strict_types' => false,
        'final_internal_class' => false,
        'mb_str_functions' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'operator_linebreak' => true,
	'phpdoc_to_comment' => ['allow_before_return_statement' => true, 'ignored_tags' => ['var', 'phpstan-ignore', 'phpstan-ignore-next-line', 'psalm-suppress']],
	'phpdoc_align' => false,
        'single_line_empty_body' => true,
        'static_lambda' => false,
        'string_implicit_backslashes' => ['double_quoted' => 'escape', 'single_quoted' => 'ignore', 'heredoc' => 'escape'],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('GPBMetadata')
        ->in('./src')
    )
    ;
