<?php

declare(strict_types=1);

$header = <<<'EOF'
This file is part of Hyperf.

@link     https://www.hyperf.io
@document https://hyperf.wiki
@contact  group@hyperf.io
@license  https://github.com/hyperf/hyperf/blob/master/LICENSE
EOF;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'               => true,
        '@Symfony'            => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer'         => true,
        'header_comment'      => [
            'comment_type' => 'PHPDoc',
            'header'       => $header,
            'separate'     => 'none',
            'location'     => 'after_declare_strict',
        ],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'declare',
            ],
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author',
            ],
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class', 'function', 'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'single_line_comment_style' => [
            'comment_types' => [
            ],
        ],
        'yoda_style' => [
            'always_move_variable' => false,
            'equal'                => false,
            'identical'            => false,
        ],
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags'  => [
                'param', 'throws', 'type', 'var', 'property',
            ],
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'constant_case' => [
            'case' => 'lower',
        ],
        'class_attributes_separation'       => true,
        'combine_consecutive_unsets'        => true,
        'declare_strict_types'              => true,
        'linebreak_after_opening_tag'       => true,
        'lowercase_static_reference'        => true,
        'no_useless_else'                   => true,
        'no_unused_imports'                 => true,
        'not_operator_with_successor_space' => true,
        'not_operator_with_space'           => false,
        'ordered_class_elements'            => true,
        'php_unit_strict'                   => false,
        'phpdoc_separation'                 => false,
        // PHPDoc summary should end in either a full stop, exclamation mark, or question mark.
        'phpdoc_summary'                    => false,
        'single_quote'                      => true,
        'standardize_not_equals'            => true,
        'multiline_comment_opening_closing' => true,
        // ???????????????????????????????????????
        'binary_operator_spaces' => [
            'default' => 'align_single_space',
        ],
        'align_multiline_comment'                    => [
            'comment_type' => 'phpdocs_only',
        ],
        'no_useless_return'                          => true, // ?????????????????????return??????
        'self_accessor'                              => true, // ????????????????????? self ????????????
        'php_unit_construct'                         => true,
        'no_singleline_whitespace_before_semicolons' => true, // ??????????????????????????????????????????
        'no_empty_statement'                         => true, // ???????????????
        'no_whitespace_in_blank_line'                => true, // ????????????????????????
        'array_indentation'                          => true, // ???????????????????????????????????????
        'no_superfluous_phpdoc_tags'                 => false, // ????????????????????????
        'lowercase_cast'                             => false, // ??????????????????
        'no_blank_lines_after_class_opening'         => true,
        'phpdoc_single_line_var_spacing'             => true,
        'phpdoc_indent'                              => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
