<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(false)
    ->exclude([
        'var',
        'vendor',
        'node_modules',
    ])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR12' => true,
        'psr_autoloading' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder);

return $config;
