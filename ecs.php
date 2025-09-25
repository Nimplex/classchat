<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths(array(__DIR__ . '/src', __DIR__ . '/tests'))
    ->withConfiguredRule(
        ArraySyntaxFixer::class,
        array('syntax' => 'long')
    )
    ->withRules(array(
        ListSyntaxFixer::class,
    ))
    ->withPreparedSets(psr12: true);
