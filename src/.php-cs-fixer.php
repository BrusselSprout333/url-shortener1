<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(false)
    ->notPath('/vendor')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1'           => true,
        '@PSR12'          => true,
        'psr_autoloading' => true
    ])
    ->setFinder($finder);

return $config;

