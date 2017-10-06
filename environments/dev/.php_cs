<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        // '@PSR2' => true,
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => true,
        'braces' => true,
        'cast_spaces' => ['space' => 'single'],
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'elseif' => true,
        'full_opening_tag' => true,
        'function_declaration' => true,
        'is_null' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'method_argument_space' => ['ensure_fully_multiline' => true],
        'method_separation' => true,
        'modernize_types_casting' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_closing_tag' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_whitespace' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,

        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'non_printable_character' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'psr4' => true,
        'return_type_declaration' => true,
        'self_accessor' => true,
        'short_scalar_cast' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline_array' => true,
        'visibility_required' => true,
    ])
    ->setFinder($finder);
