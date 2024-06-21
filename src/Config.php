<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig;

use Clinkards\PhpCsFixerConfig\Sniffs\RemoveReadonlyPropertyAttributeOnReadonlyClass;
use Gordinskiy\LineLengthChecker\Rules\LineLengthLimit;
use PhpCsFixer\Config as BaseConfig;

class Config extends BaseConfig
{
    private array $defaultRules = [
        '@Symfony' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'declare_strict_types' => true,
        'fully_qualified_strict_types' => false,
        'Gordinskiy/line_length_limit' => true,
        'Clinkards/remove_readonly_property' => true,
        'single_line_throw' => false,
    ];

    public function __construct(private array $extraRules = [])
    {
        parent::__construct('Clinkards - Coding Standard');

        $this->registerCustomFixers([
            new RemoveReadonlyPropertyAttributeOnReadonlyClass(),
            new LineLengthLimit(),
        ]);
    }

    public function getRules(): array
    {
        return array_merge($this->defaultRules, $this->extraRules);
    }
}
