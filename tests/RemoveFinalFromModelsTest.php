<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig\Tests;

use Clinkards\PhpCsFixerConfig\Sniffs\RemoveFinalFromModels;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

final class RemoveFinalFromModelsTest extends TestCase
{
    public function testFixesFinalModels()
    {
        $fixer = new RemoveFinalFromModels();
        
        $expected = '<?php namespace Some\Random\Domain\Model; class Foo { public int $bar; }';
        $input = '<?php namespace Some\Random\Domain\Model; final class Foo { public int $bar; }';
        
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($expected, $tokens->generateCode());
    }

    public function testDoesNotRemoveFinalFromOtherNamespaces()
    {
        $fixer = new RemoveFinalFromModels();
        
        $expected = '<?php namespace Some\Random\Domain\Service; final class Foo { public int $bar; }';
        $input = '<?php namespace Some\Random\Domain\Service; final class Foo { public int $bar; }';
        
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($expected, $tokens->generateCode());
    }
}
