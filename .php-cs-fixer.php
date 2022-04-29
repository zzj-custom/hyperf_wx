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
        // 等号对齐、数字箭头符号对齐
        'binary_operator_spaces' => [
            'default' => 'align_single_space',
        ],
        'align_multiline_comment'                    => [
            'comment_type' => 'phpdocs_only',
        ],
        'no_useless_return'                          => true, // 删除没有使用的return语句
        'self_accessor'                              => true, // 在当前类中使用 self 代替类名
        'php_unit_construct'                         => true,
        'no_singleline_whitespace_before_semicolons' => true, // 禁止只有单行空格和分号的写法
        'no_empty_statement'                         => true, // 多余的分号
        'no_whitespace_in_blank_line'                => true, // 删除空行中的空格
        'array_indentation'                          => true, // 数组的每个元素必须缩进一次
        'no_superfluous_phpdoc_tags'                 => false, // 移出没有用的注释
        'lowercase_cast'                             => false, // 类型强制小写
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
