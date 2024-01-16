<?php

namespace Clinkards\PhpCsFixerConfig;

use PhpCsFixer\Config as BaseConfig;

class Config extends BaseConfig
{
    private array $defaultRules = [
        '@PSR12' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'declare_strict_types' => true,
    ];

    public function __construct(private array $extraRules = [])
    {
        parent::__construct('Clinkards - Coding Standard');
    }

    public function getRules(): array
    {
        return array_merge($this->defaultRules, $this->extraRules);
    }
}
