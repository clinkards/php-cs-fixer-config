<?php

declare(strict_types=1);

namespace Clinkards\PhpCsFixerConfig\Tests;

use Clinkards\PhpCsFixerConfig\Sniffs\RemoveReadonlyPropertyAttributeOnReadonlyClass;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

final class RemoveReadonlyPropertyAttributeOnReadonlyClassTest extends TestCase
{
    public function testFixesReadOnlyProperties()
    {
        $fixer = new RemoveReadonlyPropertyAttributeOnReadonlyClass();
        
        $expected = '<?php readonly class Foo { public int $bar; }';
        $input = '<?php readonly class Foo { readonly public int $bar; }';
        
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($expected, $tokens->generateCode());
    }

    public function testDoesNotAffectNonReadOnlyClasses()
    {
        $fixer = new RemoveReadonlyPropertyAttributeOnReadonlyClass();
        
        $expected = '<?php class Foo { readonly public int $bar; }';
        $input = '<?php class Foo { readonly public int $bar; }';
        
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        $this->assertSame($expected, $tokens->generateCode());
    }
}
